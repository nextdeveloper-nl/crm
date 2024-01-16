<?php

namespace NextDeveloper\CRM\Http\Transformers\AbstractTransformers;

use NextDeveloper\CRM\Database\Models\UserManagers;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class UserManagersTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AbstractUserManagersTransformer extends AbstractTransformer
{

    /**
     * @param UserManagers $model
     *
     * @return array
     */
    public function transform(UserManagers $model)
    {
                        $crmUserId = \NextDeveloper\CRM\Database\Models\Users::where('id', $model->crm_user_id)->first();
                    $iamUserId = \NextDeveloper\IAM\Database\Models\Users::where('id', $model->iam_user_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'crm_user_id'  =>  $crmUserId ? $crmUserId->uuid : null,
            'iam_user_id'  =>  $iamUserId ? $iamUserId->uuid : null,
            'created_at'  =>  $model->created_at ? $model->created_at->toIso8601String() : null,
            'deleted_at'  =>  $model->deleted_at ? $model->deleted_at->toIso8601String() : null,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n







}
