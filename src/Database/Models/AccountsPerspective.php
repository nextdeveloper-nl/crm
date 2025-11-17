<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\AccountsPerspectiveObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;
use NextDeveloper\Commons\Database\Traits\HasObject;

/**
 * AccountsPerspective model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $account_owners_fullname
 * @property string $account_owners_email
 * @property string $email
 * @property string $account_owners_phone_number
 * @property integer $common_domain_id
 * @property string $domain_name
 * @property integer $common_country_id
 * @property string $country_name
 * @property string $phone_number
 * @property string $description
 * @property integer $iam_account_type_id
 * @property string $account_type
 * @property boolean $is_paying_customer
 * @property integer $common_city_id
 * @property string $position
 * @property integer $risk_level
 * @property integer $iam_user_id
 * @property integer $iam_account_id
 * @property integer $total_user_count
 * @property integer $registered_user_count
 * @property boolean $is_disabled
 * @property string $disabling_reason
 * @property boolean $is_suspended
 * @property string $suspension_reason
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class AccountsPerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_accounts_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'name',
            'account_owners_fullname',
            'account_owners_email',
            'email',
            'account_owners_phone_number',
            'common_domain_id',
            'domain_name',
            'common_country_id',
            'country_name',
            'phone_number',
            'description',
            'iam_account_type_id',
            'account_type',
            'is_paying_customer',
            'common_city_id',
            'position',
            'risk_level',
            'iam_user_id',
            'iam_account_id',
            'total_user_count',
            'registered_user_count',
            'is_disabled',
            'disabling_reason',
            'is_suspended',
            'suspension_reason',
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
    'id' => 'integer',
    'name' => 'string',
    'account_owners_fullname' => 'string',
    'account_owners_email' => 'string',
    'email' => 'string',
    'account_owners_phone_number' => 'string',
    'common_domain_id' => 'integer',
    'domain_name' => 'string',
    'common_country_id' => 'integer',
    'country_name' => 'string',
    'phone_number' => 'string',
    'description' => 'string',
    'iam_account_type_id' => 'integer',
    'account_type' => 'string',
    'is_paying_customer' => 'boolean',
    'common_city_id' => 'integer',
    'position' => 'string',
    'risk_level' => 'integer',
    'total_user_count' => 'integer',
    'registered_user_count' => 'integer',
    'is_disabled' => 'boolean',
    'disabling_reason' => 'string',
    'is_suspended' => 'boolean',
    'suspension_reason' => 'string',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
    ];

    /**
     We are casting data fields.
     *
     @var array
     */
    protected $dates = [
    'created_at',
    'updated_at',
    'deleted_at',
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
        parent::observe(AccountsPerspectiveObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_accounts_perspective');

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
