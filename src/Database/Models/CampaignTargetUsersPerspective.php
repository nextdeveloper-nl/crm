<?php

namespace NextDeveloper\CRM\Database\Models;

use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\CampaignTargetUsersPerspectiveObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Database\Traits\HasObject;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * CampaignTargetUsersPerspective model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $crm_campaign_id
 * @property string $campaign_uuid
 * @property string $campaign_name
 * @property string $campaign_status
 * @property integer $crm_target_id
 * @property string $target_name
 * @property integer $crm_user_id
 * @property string $fullname
 * @property string $email
 * @property string $phone_number
 * @property integer $iam_account_id
 * @property string $account_name
 * @property integer $responsible_account_id
 * @property string $responsible_account
 */
class CampaignTargetUsersPerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;

    public $timestamps = false;

    protected $table = 'crm_campaign_target_users_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'crm_campaign_id',
            'campaign_uuid',
            'campaign_name',
            'campaign_status',
            'crm_target_id',
            'target_name',
            'crm_user_id',
            'fullname',
            'email',
            'phone_number',
            'iam_account_id',
            'account_name',
            'responsible_account_id',
            'responsible_account',
    ];

    /**
      Here we have the fulltext fields. We can use these for fulltext search if enabled.
     */
    protected $fullTextFields = [

    ];

    /**
     @var array
     */
    protected $appends = [

    ];

    /**
     We are casting fields to objects so that we can work on them better
     *
     @var array
     */
    protected $casts = [
    'crm_campaign_id' => 'integer',
    'campaign_name' => 'string',
    'campaign_status' => 'string',
    'crm_target_id' => 'integer',
    'target_name' => 'string',
    'crm_user_id' => 'integer',
    'fullname' => 'string',
    'email' => 'string',
    'phone_number' => 'string',
    'account_name' => 'string',
    'responsible_account_id' => 'integer',
    'responsible_account' => 'string',
    ];

    /**
     We are casting data fields.
     *
     @var array
     */
    protected $dates = [

    ];

    /**
     @var array
     */
    protected $with = [

    ];

    /**
     @var int
     */
    protected $perPage = 20;

    /**
     @return void
     */
    public static function boot()
    {
        parent::boot();

        //  We create and add Observer even if we wont use it.
        parent::observe(CampaignTargetUsersPerspectiveObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_campaign_target_users_perspective');

        if(!$modelScopes) { $modelScopes = [];
        }
        if (!$globalScopes) { $globalScopes = [];
        }

        $scopes = array_merge(
            $globalScopes,
            $modelScopes
        );

        if($scopes) {
            foreach ($scopes as $scope) {
                static::addGlobalScope(app($scope));
            }
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
