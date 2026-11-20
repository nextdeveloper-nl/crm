<?php

namespace NextDeveloper\CRM\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\AccountContactShares;
use NextDeveloper\CRM\Services\AbstractServices\AbstractAccountContactSharesService;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * This class is responsible from managing the data for AccountContactShares
 *
 * Class AccountContactSharesService.
 *
 * A share lets one user of the owner's account see a contact link whose visibility is "specific". Only the owner of
 * the link manages its shares.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class AccountContactSharesService extends AbstractAccountContactSharesService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * Shares an own link with a user of the current account. Sharing twice returns the existing share.
     */
    public static function create(array $data)
    {
        unset($data['iam_user_id'], $data['iam_account_id']);

        $link = AccountContactsService::findOwned($data['crm_account_contact_id'] ?? null);

        // The target must be a member of the acting account: a link can never be shared across tenants.
        $user = Users::where('uuid', $data['shared_iam_user_id'] ?? null)->first();

        $isMember = $user && DB::table('iam_account_user')
            ->where('iam_account_id', UserHelper::currentAccount()->id)
            ->where('iam_user_id', $user->id)
            ->exists();

        if (!$isMember) {
            throw (new ModelNotFoundException())->setModel(Users::class, [$data['shared_iam_user_id'] ?? null]);
        }

        $existing = AccountContactShares::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_account_contact_id', $link->id)
            ->where('shared_iam_user_id', $user->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        return AccountContactShares::create([
            'crm_account_contact_id' => $link->id,
            'shared_iam_user_id'     => $user->id,
            'iam_user_id'            => UserHelper::me()->id,
            'iam_account_id'         => UserHelper::currentAccount()->id,
        ])->fresh();
    }

    /** Shares are never edited; remove and create again. */
    public static function update($id, array $data)
    {
        throw new NotAllowedException('A share cannot be updated.');
    }

    /** Removes a share; only the owner of the link may do this. */
    public static function delete($id)
    {
        $share = AccountContactShares::withoutGlobalScope(AuthorizationScope::class)
            ->where('uuid', $id)
            ->where('iam_user_id', UserHelper::me()->id)
            ->first();

        if (!$share) {
            throw new NotAllowedException(
                'We cannot find the related object to delete. Maybe you are not the owner of this contact?'
            );
        }

        return $share->delete();
    }
}
