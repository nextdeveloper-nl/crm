<?php

namespace NextDeveloper\CRM\Http\Transformers\AbstractTransformers;

use NextDeveloper\CRM\Database\Models\CrmUser;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class CrmUserTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AbstractCrmUserTransformer extends AbstractTransformer {

    /**
     * @param CrmUser $model
     *
     * @return array
     */
    public function transform(CrmUser $model) {
                        $iamUserId = \NextDeveloper\IAM\Database\Models\IamUser::where('id', $model->iam_user_id)->first();
            
        return $this->buildPayload([
'id'  =>  $model->uuid,
'iam_user_id'  =>  $iamUserId ? $iamUserId->uuid : null,
'position'  =>  $model->position,
'job'  =>  $model->job,
'job_description'  =>  $model->job_description,
'hobbies'  =>  $model->hobbies,
'city'  =>  $model->city,
'email_risk'  =>  $model->email_risk,
'relationship_status'  =>  $model->relationship_status,
'is_evangelist'  =>  $model->is_evangelist == 1 ? true : false,
'martial_status'  =>  $model->martial_status,
'education'  =>  $model->education,
'child_count'  =>  $model->child_count,
'created_at'  =>  $model->created_at,
'updated_at'  =>  $model->updated_at,
'deleted_at'  =>  $model->deleted_at,
    ]);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
