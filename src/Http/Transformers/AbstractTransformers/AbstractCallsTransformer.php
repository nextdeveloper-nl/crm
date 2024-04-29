<?php

namespace NextDeveloper\CRM\Http\Transformers\AbstractTransformers;

use NextDeveloper\CRM\Database\Models\Calls;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class CallsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AbstractCallsTransformer extends AbstractTransformer
{

    /**
     * @param Calls $model
     *
     * @return array
     */
    public function transform(Calls $model)
    {
                        $iamUserId = \NextDeveloper\IAM\Database\Models\Users::where('id', $model->iam_user_id)->first();
                    $crmAccountId = \NextDeveloper\CRM\Database\Models\Accounts::where('id', $model->crm_account_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'title'  =>  $model->title,
            'description'  =>  $model->description,
            'iam_user_id'  =>  $iamUserId ? $iamUserId->uuid : null,
            'iam_account_it'  =>  $model->iam_account_it,
            'crm_account_id'  =>  $crmAccountId ? $crmAccountId->uuid : null,
            'disposition'  =>  $model->disposition,
            'duration'  =>  $model->duration,
            'from_number'  =>  $model->from_number,
            'to_number'  =>  $model->to_number,
            'call_direction'  =>  $model->call_direction,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            'deleted_at'  =>  $model->deleted_at,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
