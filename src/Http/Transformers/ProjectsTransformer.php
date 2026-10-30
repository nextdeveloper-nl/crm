<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Database\Models\Projects;
use NextDeveloper\IAM\Database\Models\Accounts as IamAccounts;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractProjectsTransformer;

/**
 * Class ProjectsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class ProjectsTransformer extends AbstractProjectsTransformer
{

    /**
     * @param Projects $model
     *
     * @return array
     */
    public function transform(Projects $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Projects', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        // We build the payload here instead of calling parent::transform():
        //  - the generated parent exposes the external provider `token` (a secret) in every API response
        //  - the generated parent resolves `project_id` through a non-existent model class, but project_id is
        //    the external provider's id (plain text), not a reference to another table
        $crmAccount = Accounts::where('id', $model->crm_account_id)->first();
        $iamUser = Users::where('id', $model->iam_user_id)->first();
        $iamAccount = IamAccounts::where('id', $model->iam_account_id)->first();
        $crmOpportunity = Opportunities::where('id', $model->crm_opportunity_id)->first();

        $transformed = $this->buildPayload(
            [
                'id'                 => $model->uuid,
                'name'               => $model->name,
                'url'                => $model->url,
                'project_id'         => $model->project_id,
                'crm_account_id'     => $crmAccount ? $crmAccount->uuid : null,
                'iam_user_id'        => $iamUser ? $iamUser->uuid : null,
                'iam_account_id'     => $iamAccount ? $iamAccount->uuid : null,
                'created_at'         => $model->created_at,
                'updated_at'         => $model->updated_at,
                'deleted_at'         => $model->deleted_at,
                'crm_opportunity_id' => $crmOpportunity ? $crmOpportunity->uuid : null,
            ]
        );

        Cache::set(
            CacheHelper::getKey('Projects', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
