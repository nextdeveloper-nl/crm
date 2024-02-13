<?php

namespace NextDeveloper\CRM\Http\Transformers\AbstractTransformers;

use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class OpportunitiesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AbstractOpportunitiesTransformer extends AbstractTransformer
{

    /**
     * @param Opportunities $model
     *
     * @return array
     */
    public function transform(Opportunities $model)
    {
                        $iamAccountId = \NextDeveloper\IAM\Database\Models\Accounts::where('id', $model->iam_account_id)->first();
                    $iamUserId = \NextDeveloper\IAM\Database\Models\Users::where('id', $model->iam_user_id)->first();
                    $crmAccountId = \NextDeveloper\CRM\Database\Models\Accounts::where('id', $model->crm_account_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'name'  =>  $model->name,
            'description'  =>  $model->description,
            'probability'  =>  $model->probability,
            'stage'  =>  $model->stage,
            'source'  =>  $model->source,
            'income'  =>  $model->income,
            'deadline'  =>  $model->deadline,
            'iam_account_id'  =>  $iamAccountId ? $iamAccountId->uuid : null,
            'iam_user_id'  =>  $iamUserId ? $iamUserId->uuid : null,
            'crm_account_id'  =>  $crmAccountId ? $crmAccountId->uuid : null,
            'tags'  =>  $model->tags,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            'deleted_at'  =>  $model->deleted_at,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n
















}
