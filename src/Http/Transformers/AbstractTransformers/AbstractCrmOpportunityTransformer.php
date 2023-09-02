<?php

namespace NextDeveloper\CRM\Http\Transformers\AbstractTransformers;

use NextDeveloper\CRM\Database\Models\CrmOpportunity;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class CrmOpportunityTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AbstractCrmOpportunityTransformer extends AbstractTransformer {

    /**
     * @param CrmOpportunity $model
     *
     * @return array
     */
    public function transform(CrmOpportunity $model) {
                        $iamAccountId = \NextDeveloper\IAM\Database\Models\IamAccount::where('id', $model->iam_account_id)->first();
            
        return $this->buildPayload([
'id'  =>  $model->uuid,
'name'  =>  $model->name,
'phone_number'  =>  $model->phone_number,
'description'  =>  $model->description,
'probability'  =>  $model->probability,
'stage'  =>  $model->stage,
'source'  =>  $model->source,
'income'  =>  $model->income,
'deadline'  =>  $model->deadline,
'iam_account_id'  =>  $iamAccountId ? $iamAccountId->uuid : null,
'created_at'  =>  $model->created_at,
'updated_at'  =>  $model->updated_at,
'deleted_at'  =>  $model->deleted_at,
    ]);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n

}
