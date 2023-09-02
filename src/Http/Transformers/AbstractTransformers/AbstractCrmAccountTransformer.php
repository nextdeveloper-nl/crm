<?php

namespace NextDeveloper\CRM\Http\Transformers\AbstractTransformers;

use NextDeveloper\CRM\Database\Models\CrmAccount;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class CrmAccountTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AbstractCrmAccountTransformer extends AbstractTransformer {

    /**
     * @param CrmAccount $model
     *
     * @return array
     */
    public function transform(CrmAccount $model) {
                        $iamAccountId = \NextDeveloper\IAM\Database\Models\IamAccount::where('id', $model->iam_account_id)->first();
            
        return $this->buildPayload([
'id'  =>  $model->uuid,
'is_paying_customer'  =>  $model->is_paying_customer,
'risk_level'  =>  $model->risk_level,
'city'  =>  $model->city,
'position'  =>  $model->position,
'iam_account_id'  =>  $iamAccountId ? $iamAccountId->uuid : null,
'created_at'  =>  $model->created_at,
'updated_at'  =>  $model->updated_at,
'deleted_at'  =>  $model->deleted_at,
    ]);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n

}
