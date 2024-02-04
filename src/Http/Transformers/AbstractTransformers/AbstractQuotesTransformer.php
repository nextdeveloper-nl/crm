<?php

namespace NextDeveloper\CRM\Http\Transformers\AbstractTransformers;

use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class QuotesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AbstractQuotesTransformer extends AbstractTransformer
{

    /**
     * @param Quotes $model
     *
     * @return array
     */
    public function transform(Quotes $model)
    {
                        $iamAccountId = \NextDeveloper\IAM\Database\Models\Accounts::where('id', $model->iam_account_id)->first();
                    $iamUserId = \NextDeveloper\IAM\Database\Models\Users::where('id', $model->iam_user_id)->first();
                    $crmOpportunitiesId = \NextDeveloper\CRM\Database\Models\Opportunities::where('id', $model->crm_opportunities_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'iam_account_id'  =>  $iamAccountId ? $iamAccountId->uuid : null,
            'iam_user_id'  =>  $iamUserId ? $iamUserId->uuid : null,
            'crm_opportunities_id'  =>  $crmOpportunitiesId ? $crmOpportunitiesId->uuid : null,
            'name'  =>  $model->name,
            'description'  =>  $model->description,
            'amount'  =>  $model->amount,
            'detailed_amount'  =>  $model->detailed_amount,
            'suggested_price'  =>  $model->suggested_price,
            'suggested_currency_code'  =>  $model->suggested_currency_code,
            'status'  =>  $model->status,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            'deleted_at'  =>  $model->deleted_at,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n










}
