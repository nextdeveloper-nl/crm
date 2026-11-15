<?php

namespace NextDeveloper\CRM\Services\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;
use NextDeveloper\Commons\Exceptions\NotFoundException;
use NextDeveloper\Commons\Exceptions\DataTypeException;
use NextDeveloper\CRM\Database\Models\Projects;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ProjectClientService
{
    protected Client $client;
    protected string $projectId;
    protected string $baseUrl;
    protected string $token;

    /** curl --resolve entries pinning the connection to the addresses that passed assertPublicUrl() */
    protected array $pinnedResolve = [];


    /**
     * @throws NotFoundException|DataTypeException
     */
    public function __construct(Projects $project)
    {
        $this->validateProjectData($project);
        $this->baseUrl = $this->parseClientUrl($project->url);
        $this->projectId = $project->project_id;
        $this->token = $project->token;

        $this->initializeClient();
    }

    /**
     * Validate essential project data
     *
     * @param Projects $project
     * @throws NotFoundException
     */
    protected function validateProjectData(Projects $project): void
    {
        if (empty($project->project_id)) {
            throw new NotFoundException('Project ID cannot be empty');
        }

        if (empty($project->token)) {
            throw new NotFoundException('Project token cannot be empty');
        }

        if (empty($project->url)) {
            throw new NotFoundException('Project URL cannot be empty');
        }
    }

    /**
     * Initialize the Guzzle client
     * @throws InvalidArgumentException
     */
    protected function initializeClient(): void
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            // SSRF: the URL was vetted once; a redirect would send the request (and the token) to an unvetted host.
            'allow_redirects' => false,
            'curl' => $this->pinnedResolve ? [CURLOPT_RESOLVE => $this->pinnedResolve] : [],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Parse and validate the client URL
     *
     * @param string $url
     * @return string
     * @throws DataTypeException
     */
    protected function parseClientUrl(string $url): string
    {
        $parsedUrl = parse_url($url);

        if (!$parsedUrl || !isset($parsedUrl['host'])) {
            throw new DataTypeException('Invalid URL provided');
        }

        switch ($parsedUrl['host']) {
            case 'github.com':
                return 'https://api.github.com/';
            case 'gitlab.com':
                return 'https://gitlab.com/api/v4/';
            default:
                // Multi-tenancy/SSRF: a tenant-supplied URL must not point the server (and the stored token) at
                // internal addresses such as localhost, the metadata endpoint or private networks.
                $ips = self::assertPublicUrl($url);

                $base = $this->ensureUrlScheme($url);
                $host = parse_url($base, PHP_URL_HOST);

                // Pin the connection to the vetted addresses (DNS rebinding: the name must not resolve to something
                // else between this check and the request). IP literals need no pinning.
                if ($host && !filter_var(trim($host, '[]'), FILTER_VALIDATE_IP)) {
                    $port = parse_url($base, PHP_URL_PORT) ?: (parse_url($base, PHP_URL_SCHEME) === 'http' ? 80 : 443);
                    $this->pinnedResolve = [$host . ':' . $port . ':' . implode(',', $ips)];
                }

                return $base;
        }
    }

    /**
     * Every A and AAAA address of a host (IPv6 included, so an AAAA-only internal host cannot slip through).
     *
     * @return string[]
     */
    private static function resolveAddresses(string $host): array
    {
        $ips = [];

        foreach (@dns_get_record($host, DNS_A | DNS_AAAA) ?: [] as $record) {
            $ips[] = $record['ip'] ?? $record['ipv6'] ?? null;
        }

        return array_values(array_filter($ips));
    }

    /**
     * Rejects non-http(s) URLs and hosts that are, or resolve to, private/loopback/reserved addresses.
     * Returns the vetted addresses so the caller can pin the connection to them.
     *
     * @throws DataTypeException
     */
    public static function assertPublicUrl(string $url): array
    {
        $candidate = parse_url($url, PHP_URL_SCHEME) ? $url : 'https://' . $url;
        $scheme = strtolower((string) parse_url($candidate, PHP_URL_SCHEME));
        $host = parse_url($candidate, PHP_URL_HOST);

        if (!in_array($scheme, ['http', 'https'], true) || !$host) {
            throw new DataTypeException('Invalid URL provided');
        }

        $host = trim($host, '[]');
        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : self::resolveAddresses($host);

        if (!$ips) {
            throw new DataTypeException('The project URL host could not be resolved');
        }

        foreach ($ips as $ip) {
            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new DataTypeException('The project URL must point to a public address');
            }
        }

        return array_values($ips);
    }

    /**
     * Ensure the URL has a scheme
     *
     * @param string $url
     * @return string
     */
    protected function ensureUrlScheme(string $url): string
    {
        if (!parse_url($url, PHP_URL_SCHEME)) {
            $url = 'https://' . $url;
        }
        return rtrim($url, '/');
    }

    /**
     * Make an API request
     *
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return array
     * @throws GuzzleException
     * @throws DataTypeException
     */
    protected function makeRequest(string $method, string $endpoint, array $options = []): array
    {
        $defaultOptions = [
            'query' => ['private_token' => $this->token],
        ];

        $options = array_merge_recursive($defaultOptions, $options);

        try {
            $response = $this->client->request($method, $endpoint, $options);
            return $this->handleResponse($response);
        } catch (GuzzleException | DataTypeException  $e) {
            Log::error("CRM@ProjectClientService: Error making request to {$this->baseUrl}{$endpoint}");
            throw $e;
        }
    }

    /**
     * Handle the API response
     *
     * @param ResponseInterface $response
     * @return array
     * @throws DataTypeException
     */
    protected function handleResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();
        $decodedBody = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new DataTypeException('Invalid JSON response from API');
        }

        return $decodedBody;
    }

    /**
     * Get project data
     *
     * @return array
     * @throws GuzzleException|DataTypeException
     */
    public function getProjectData(): array
    {
        return $this->makeRequest('GET', "projects/{$this->projectId}");
    }

    /**
     * Get project issues
     *
     * @param array $params Optional parameters for filtering issues
     * @return array
     * @throws GuzzleException|DataTypeException
     */
    public function getProjectIssues(array $params = []): array
    {
        return $this->makeRequest('GET', "projects/{$this->projectId}/issues", ['query' => $params]);
    }


    /**
     * Get project README content
     *
     * @param array $params Optional parameters
     * @throws GuzzleException
     */
    public function getProjectReadme(array $params = [])
    {

        $defaultOptions = [
            'query' => ['private_token' => $this->token],
        ];

        $options = array_merge_recursive($defaultOptions, $params);

        $request = $this->client->request(
            'GET',
            "projects/{$this->projectId}/repository/files/README.md/raw",
            $options);

        return $request->getBody()->getContents();

    }

}