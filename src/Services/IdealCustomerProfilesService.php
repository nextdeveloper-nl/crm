<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Services\AbstractServices\AbstractIdealCustomerProfilesService;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * This class is responsible from managing the data for IdealCustomerProfiles
 *
 * Class IdealCustomerProfilesService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class IdealCustomerProfilesService extends AbstractIdealCustomerProfilesService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function create($data)
    {
        $crmAccount = Accounts::where('iam_account_id', UserHelper::currentAccount()->id)->first();

        $campaign = CampaignsService::create([
            'name'  =>  $data['marketing_campaign_name']
        ]);

        $target = TargetsService::create([
            'name'  =>  $data['lead_list_name']
        ]);

        $data['name']               = $data['marketing_campaign_name'];
        $data['search_criteria']    = $data;
        $data['crm_target_id']      = $target->id;
        $data['crm_account_id']     = $crmAccount->id;

        unset($data['message']);
        unset($data['question']);
        unset($data['answer_options']);
        unset($data['marketing_campaign_name']);
        unset($data['lead_list_name']);

        return parent::create($data);
    }
}
