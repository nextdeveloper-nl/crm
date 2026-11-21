<?php

namespace NextDeveloper\CRM\Http\Transformers;

use NextDeveloper\CRM\Authorization\Roles\SalesAdminRole;
use NextDeveloper\CRM\Authorization\Roles\SalesManagerRole;
use NextDeveloper\CRM\Database\Models\SalesPeoplePerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractSalesPeoplePerspectiveTransformer;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Class SalesPeoplePerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class SalesPeoplePerspectiveTransformer extends AbstractSalesPeoplePerspectiveTransformer
{

    /**
     * Fields hidden from callers that are not allowed to see a colleague's personal data.
     */
    private const PERSONAL_FIELDS = ['nin', 'birthday', 'phone_number'];

    /**
     * Same payload as AbstractSalesPeoplePerspectiveTransformer, built here for speed. The generated parent looks up
     * the country, language, user and account of EVERY row through scoped models; each of those queries runs the
     * authorization scope (role lookups plus dozens of log lines), so a 100 row page of this list hung in production.
     * The foreign keys only need their uuid, so they are resolved once per distinct id and kept for the request.
     *
     * @param SalesPeoplePerspective $model
     *
     * @return array
     */
    public function transform(SalesPeoplePerspective $model)
    {
        $payload = $this->buildPayload([
            'id'  =>  $model->uuid,
            'name'  =>  $model->name,
            'surname'  =>  $model->surname,
            'fullname'  =>  $model->fullname,
            'email'  =>  $model->email,
            'about'  =>  $model->about,
            'pronoun'  =>  $model->pronoun,
            'birthday'  =>  $model->birthday,
            'nin'  =>  $model->nin,
            'common_country_id'  =>  self::uuidOf(\NextDeveloper\Commons\Database\Models\Countries::class, $model->common_country_id),
            'country'  =>  $model->country,
            'common_language_id'  =>  self::uuidOf(\NextDeveloper\Commons\Database\Models\Languages::class, $model->common_language_id),
            'language'  =>  $model->language,
            'iam_updated_at'  =>  $model->iam_updated_at,
            'phone_number'  =>  $model->phone_number,
            'is_registered'  =>  $model->is_registered,
            'is_nin_verified'  =>  $model->is_nin_verified,
            'is_email_verified'  =>  $model->is_email_verified,
            'is_phone_number_verified'  =>  $model->is_phone_number_verified,
            'profile_picture_identity'  =>  $model->profile_picture_identity,
            'position'  =>  $model->position,
            'job'  =>  $model->job,
            'job_description'  =>  $model->job_description,
            'hobbies'  =>  $model->hobbies,
            'city'  =>  $model->city,
            'email_risk'  =>  $model->email_risk,
            'relationship_status'  =>  $model->relationship_status,
            'is_evangelist'  =>  $model->is_evangelist,
            'is_single'  =>  $model->is_single,
            'education'  =>  $model->education,
            'child_count'  =>  $model->child_count,
            'iam_user_id'  =>  self::uuidOf(\NextDeveloper\IAM\Database\Models\Users::class, $model->iam_user_id),
            'iam_account_id'  =>  self::uuidOf(\NextDeveloper\IAM\Database\Models\Accounts::class, $model->iam_account_id, true),
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
        ]);

        // Get a profile picture url
        $payload['profile_picture_url'] = UserHelper::getUsersProfilePictureUrl(
            $payload['email'],
            $payload['profile_picture_identity'] ?? null
        );

        // The team list is visible to every sales role of the account, but national id, birthday and phone number
        // are personal data: only the person themself, a sales manager or a sales admin gets them. Nothing is cached
        // per row on purpose (the old per-uuid cache write stored the unredacted nin and was never read).
        if (!self::canSeePersonalData($model)) {
            foreach (self::PERSONAL_FIELDS as $field) {
                if (array_key_exists($field, $payload)) {
                    $payload[$field] = null;
                }
            }
        }

        return $payload;
    }

    /**
     * uuid of the row with the given integer id, memoized on the current request. Scopes are skipped (only the uuid is
     * exposed) except for accounts: the view's iam_account_id is one arbitrary account of a multi-account user, so
     * the account uuid is only given when the caller may read that account, like the generated transformer did.
     * Accounts repeat across rows, so the scoped lookup still runs once per distinct account.
     */
    private static function uuidOf(string $modelClass, $id, bool $scoped = false): ?string
    {
        if ($id === null) {
            return null;
        }

        $attributes = request()->attributes;
        $key = 'crm.sales_people.uuid.' . $modelClass . '.' . $id;

        if (!$attributes->has($key)) {
            $query = $scoped ? $modelClass::query() : $modelClass::withoutGlobalScopes();
            $attributes->set($key, $query->where('id', $id)->value('uuid'));
        }

        return $attributes->get($key);
    }

    /**
     * The row's own person, sales managers and sales admins see the personal fields; other sales roles do not.
     *
     * UserHelper::has() and me() hit the database on every call (roles are not cached), and this runs once per row,
     * so on a 100 row page the list took ~40 seconds. Who the caller is and whether they are a manager/admin is the
     * same for every row of a request, so it is worked out once and kept on the current request (not in a static,
     * so long running workers never reuse it for another request or user).
     */
    private static function canSeePersonalData(SalesPeoplePerspective $model): bool
    {
        $attributes = request()->attributes;

        if (!$attributes->has('crm.sales_people.viewer')) {
            $attributes->set('crm.sales_people.viewer', [
                'id' => UserHelper::me()->id,
                'sees_all' => UserHelper::has(SalesManagerRole::NAME) || UserHelper::has(SalesAdminRole::NAME),
            ]);
        }

        $viewer = $attributes->get('crm.sales_people.viewer');

        return $viewer['sees_all'] || $model->iam_user_id == $viewer['id'];
    }
}
