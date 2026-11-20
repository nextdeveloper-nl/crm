<?php

namespace NextDeveloper\CRM\Http\Transformers;

use NextDeveloper\Agenda\Database\Models\Contacts;
use NextDeveloper\CRM\Database\Models\AccountContactShares;
use NextDeveloper\CRM\Database\Models\AccountContacts;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountContactsTransformer;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Class AccountContactsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AccountContactsTransformer extends AbstractAccountContactsTransformer
{
    /**
     * Adds the contact itself and the viewer relation to the link.
     *
     * Not cached on purpose: the payload depends on the viewer (is_owner, shared_with) and on the Agenda contact,
     * which can change without the link changing.
     *
     * Rows only reach this transformer through AccountContactsService, which already filtered them by link
     * visibility, so the contact may be read here without its owner scope.
     *
     * @param AccountContacts $model
     *
     * @return array
     */
    public function transform(AccountContacts $model)
    {
        $payload = parent::transform($model);

        $contact = Contacts::withoutGlobalScope(AuthorizationScope::class)->where('id', $model->agenda_contact_id)->first();

        $isOwner = (int) $model->iam_user_id === (int) UserHelper::me()->id;

        $payload['is_owner'] = $isOwner;
        $payload['contact'] = $contact ? [
            'id'         => $contact->uuid,
            'name'       => $contact->name,
            'surname'    => $contact->surname,
            'email'      => $contact->email,
            'email_work' => $contact->email_work,
            'home_phone' => $contact->home_phone,
            'cell_phone' => $contact->cell_phone,
            'fax_number' => $contact->fax_number,
            'website'    => $contact->website,
            'notes'      => $contact->notes,
            'description' => $contact->description,
        ] : null;

        // Only the owner sees who a link is shared with.
        if ($isOwner && $model->visibility === AccountContacts::VISIBILITY_SPECIFIC) {
            $userIds = AccountContactShares::withoutGlobalScope(AuthorizationScope::class)
                ->where('crm_account_contact_id', $model->id)
                ->pluck('shared_iam_user_id');

            $payload['shared_with'] = Users::whereIn('id', $userIds)->pluck('uuid')->values()->all();
        }

        return $payload;
    }
}
