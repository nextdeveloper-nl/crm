<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\Commons\Database\Models\Domains;
use NextDeveloper\IAM\Database\Models\Accounts as IamAccounts;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * Finds the existing customer iam_account for a company so CRM flows reuse it instead of creating a duplicate.
 *
 * An iam_account must exist once per company, no matter how many tenants work with it (each tenant keeps its own
 * crm_accounts record). Every CRM path that would create an iam_account for a company (sales-person "new account",
 * sheet imports, ...) must call find() first and create only when it returns null.
 *
 * Lookup order:
 *   1. domain  common_domains.name  ->  iam_accounts.common_domain_id  (a domain belongs to exactly one account)
 *   2. name    case-insensitive, whitespace-normalised name, same account type
 *
 * Deliberately a finder only: it never creates, and callers must NOT hand the found account's roles or ownership to
 * the requesting user (finding a company does not entitle the caller to its IAM account, only to a CRM record of it).
 */
class CustomerAccountResolverService
{
    public static function find(?string $domainName, ?string $name, ?int $accountTypeId = null): ?IamAccounts
    {
        $byDomain = self::findByDomain($domainName);

        if ($byDomain) {
            return $byDomain;
        }

        return self::findByName($name, $accountTypeId);
    }

    public static function findByDomain(?string $domainName): ?IamAccounts
    {
        $domainName = self::normalizeDomain($domainName);

        if ($domainName === null) {
            return null;
        }

        $domain = Domains::withoutGlobalScope(AuthorizationScope::class)
            ->whereRaw('lower(name) = ?', [$domainName])
            ->first();

        if (!$domain) {
            return null;
        }

        // Any domain of the account counts (leo4#1255): the primary one (iam_accounts.common_domain_id) or a
        // secondary one (common_domains.iam_account_id).
        return IamAccounts::withoutGlobalScope(AuthorizationScope::class)
            ->where(function ($q) use ($domain) {
                $q->where('common_domain_id', $domain->id);

                if ($domain->iam_account_id) {
                    $q->orWhere('id', $domain->iam_account_id);
                }
            })
            ->orderByRaw('case when common_domain_id = ? then 0 else 1 end', [$domain->id])
            ->orderBy('id')
            ->first();
    }

    public static function findByName(?string $name, ?int $accountTypeId = null): ?IamAccounts
    {
        $name = self::normalizeName($name);

        if ($name === null) {
            return null;
        }

        $query = IamAccounts::withoutGlobalScope(AuthorizationScope::class)
            ->whereRaw("lower(regexp_replace(trim(name), '\\s+', ' ', 'g')) = ?", [$name]);

        if ($accountTypeId !== null) {
            $query->where('iam_account_type_id', $accountTypeId);
        }

        return $query->orderBy('id')->first();
    }

    /**
     * Lower-cased host without scheme, "www." and path; null when nothing usable is left.
     */
    public static function normalizeDomain(?string $domainName): ?string
    {
        if ($domainName === null) {
            return null;
        }

        $domain = strtolower(trim($domainName));
        $domain = preg_replace('#^[a-z][a-z0-9+.-]*://#', '', $domain);
        $domain = preg_replace('#^www\.#', '', $domain);
        $domain = explode('/', $domain)[0];

        return $domain === '' ? null : $domain;
    }

    /**
     * Lower-cased name with collapsed whitespace; null when empty.
     */
    public static function normalizeName(?string $name): ?string
    {
        if ($name === null) {
            return null;
        }

        $name = mb_strtolower(trim(preg_replace('/\s+/', ' ', $name)));

        return $name === '' ? null : $name;
    }
}
