<?php

namespace NextDeveloper\CRM\Services;

use Illuminate\Support\Facades\Log;
use NextDeveloper\CRM\Database\Models\Accounts;
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

    private array $lastError;

    private ?Accounts $crmAccount = null;

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