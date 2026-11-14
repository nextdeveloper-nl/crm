<?php

namespace NextDeveloper\CRM\Services;

use Illuminate\Support\Facades\Log;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Helpers\TenantHelper;
use NextDeveloper\CRM\Services\AbstractServices\AbstractAccountsService;
use NextDeveloper\I18n\Helpers\i18n;
use NextDeveloper\IAM\Helpers\UserHelper;


/**
 * This class is responsible from managing the data for Accounts
 *
 * Class AccountsService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class AccountsService extends AbstractAccountsService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    private const RESPONSE_MESSAGES = [
        'account_not_found'         => 'No account found for the current user.',
        'crm_account_not_found'     => 'No CRM account found for the current user.',
        'user_not_found'            => 'No user found for the current user.',
        'identity_not_verified'     => 'You need to verify your identity to use this service.',
        'service_enable_failed'     => 'Service could not be enabled.',
        'service_enabled'           => 'Service is enabled.'
    ];

    /**
     * Multi-tenancy: stamps the owning tenant on the new CRM record. Callers that run as admin on behalf of a tenant
     * (importers, intake jobs) must pass owner_iam_account_id explicitly; everybody else gets the current account.
     * Only stamped while tenant isolation is enabled (the column exists once the DDL is applied).
     */
    public static function create(array $data)
    {
        // The owner decides which customers' people a tenant can see (TenantScope), so only trusted callers (the
        // platform account, or system code running as admin, e.g. the importers) may name the owner. Anybody else
        // always owns what they create.
        if (!self::mayChooseOwner()) {
            unset($data['owner_iam_account_id'], $data['is_created_by_owner']);

            // The customer a CRM record points at decides whose people the owner can see (TenantScope), so an
            // untrusted caller may only create a record for the account it is acting in. Anything else is refused.
            self::assertCallerBelongsToAccount($data['iam_account_id'] ?? null);
        }

        if (TenantHelper::isolationEnabled() && !array_key_exists('owner_iam_account_id', $data)) {
            $data['owner_iam_account_id'] = TenantHelper::resolveOwnerId();
        }

        return parent::create($data);
    }

    /**
     * Untrusted callers may only point a CRM record at the account they are currently acting in. Plain membership of
     * another account is not enough (a member may hold a minimal role there), and a record for a foreign customer is
     * created by trusted code only (store(), the importers), never straight from a request.
     */
    private static function assertCallerBelongsToAccount($accountUuidOrId): void
    {
        $current = UserHelper::currentAccount();

        $allowed = $accountUuidOrId === null
            || (is_int($accountUuidOrId) && (int) $accountUuidOrId === (int) $current->id)
            || (is_string($accountUuidOrId) && $accountUuidOrId === $current->uuid);

        if (!$allowed) {
            throw new \NextDeveloper\Commons\Exceptions\NotAllowedException(
                'You can only create a CRM record for the account you are currently acting in.'
            );
        }
    }

    private static function mayChooseOwner(): bool
    {
        return TenantHelper::isPlatformAccount((int) UserHelper::currentAccount()->id)
            || UserHelper::bypassRolesCheck();
    }

    private array $lastError;

    private ?Accounts $crmAccount = null;

    public static function update($id, $data) {
        // Ownership can not be transferred or claimed through an update either, and the customer a record points
        // at is immutable for untrusted callers (re-pointing it would hand them another account's people).
        if (!self::mayChooseOwner()) {
            unset($data['owner_iam_account_id'], $data['iam_account_id'], $data['is_created_by_owner']);
        }

        if(array_key_exists('sdr_questionaire', $data)) {
            if(array_key_exists('isGoodFit', $data['sdr_questionaire'])) {
                if($data['sdr_questionaire']['isGoodFit']) {
                    $data['is_sdr_qualified'] = true;
                    $data['is_sdr_qualification_required'] = false;
                }
                else {
                    $data['is_sdr_qualified'] = false;
                    $data['is_sdr_qualification_required'] = false;
                }
            }

            if (array_key_exists('phoneNumber', $data['sdr_questionaire'])) {
                $data['office_phone_number'] = $data['sdr_questionaire']['phoneNumber'];
            }

            if (array_key_exists('phoneNumberExtention', $data['sdr_questionaire'])) {
                $data['office_phone_extension'] = $data['sdr_questionaire']['phoneNumberExtention'];
            }
        }

        return parent::update($id, $data);
    }

    /**
     * Enables service for the current user after performing necessary validations
     *
     * @return array{success: bool, message: string}
     * @throws \RuntimeException When translation service is unavailable
     */
    public static function enableService(): array
    {
        try {
            $validator = new self();

            // Perform all validations
            if (!$validator->validateRequirements()) {
                return $validator->getLastError();
            }

            // Enable the service
            return $validator->enableServiceForAccount();

        } catch (\Exception $e) {
            Log::error('Service enablement failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => i18n::t(self::RESPONSE_MESSAGES['service_enable_failed'])
            ];
        }
    }

    /**
     * Validates all requirements for enabling the service
     *
     * @return bool
     */
    private function validateRequirements(): bool
    {
        // Check IAM account
        $iamAccount = UserHelper::currentAccount();
        if (!$iamAccount) {
            $this->setError(self::RESPONSE_MESSAGES['account_not_found']);
            return false;
        }

        // Check CRM account
        $this->crmAccount = Accounts::withoutGlobalScopes()
            ->where('iam_account_id', $iamAccount->id)
            ->first();

        if (!$this->crmAccount) {
            $this->setError(self::RESPONSE_MESSAGES['crm_account_not_found']);
            return false;
        }

        // Check user and verification status
        $user = UserHelper::currentUser();
        if (!$user) {
            $this->setError(self::RESPONSE_MESSAGES['user_not_found']);
            return false;
        }

        if (!$user->is_nin_verified) {
            $this->setError(self::RESPONSE_MESSAGES['identity_not_verified']);
            return false;
        }

        return true;
    }

    /**
     * Enables the service for the validated CRM account
     *
     * @return array{success: bool, message: string}
     */
    private function enableServiceForAccount(): array
    {
        $this->crmAccount->updateQuietly([
            'is_service_enabled' => true
        ]);

        return [
            'success' => $this->crmAccount->is_service_enabled,
            'message' => i18n::t(
                $this->crmAccount->is_service_enabled
                    ? self::RESPONSE_MESSAGES['service_enabled']
                    : self::RESPONSE_MESSAGES['service_enable_failed']
            )
        ];
    }

    /**
     * Sets the last error message
     *
     * @param string $message
     * @return void
     */
    private function setError(string $message): void
    {
        $this->lastError = [
            'success' => false,
            'message' => i18n::t($message)
        ];
    }

    /**
     * Gets the last error message
     *
     * @return array{success: bool, message: string}
     */
    private function getLastError(): array
    {
        return $this->lastError;
    }


}
