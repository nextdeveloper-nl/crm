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

        $data['crm_account_id'] = $crmAccount->id;

        if(array_key_exists('geographical_focus', $data))
            $data['geographical_focus'] = explode(',', $data['geographical_focus']);

        if(array_key_exists('keywords', $data))
            $data['keywords']   = explode(',', $data['keywords']);

        if(array_key_exists('verticals', $data))
            $data['verticals']   = explode(',', $data['verticals']);

        return parent::create($data);
    }
}
