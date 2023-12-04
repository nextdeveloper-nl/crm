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
                        $iamAccountsId = \NextDeveloper\IAM\Database\Models\Accounts::where('id', $model->iam_accounts_id)->first();
                    $crmProjectsId = \NextDeveloper\CRM\Database\Models\Projects::where('id', $model->crm_projects_id)->first();
                    $crmOpportunitiesId = \NextDeveloper\CRM\Database\Models\Opportunities::where('id', $model->crm_opportunities_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'iam_accounts_id'  =>  $iamAccountsId ? $iamAccountsId->uuid : null,
            'crm_projects_id'  =>  $crmProjectsId ? $crmProjectsId->uuid : null,
            'crm_opportunities_id'  =>  $crmOpportunitiesId ? $crmOpportunitiesId->uuid : null,
            'name'  =>  $model->name,
            'description'  =>  $model->description,
            'amount'  =>  $model->amount,
            'detailed_amount'  =>  $model->detailed_amount,
            'suggested_price'  =>  $model->suggested_price,
            'suggested_currency_code'  =>  $model->suggested_currency_code,
            'status'  =>  $model->status,
            'created_at'  =>  $model->created_at ? $model->created_at->toIso8601String() : null,
            'updated_at'  =>  $model->updated_at ? $model->updated_at->toIso8601String() : null,
            'deleted_at'  =>  $model->deleted_at ? $model->deleted_at->toIso8601String() : null,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n




}
