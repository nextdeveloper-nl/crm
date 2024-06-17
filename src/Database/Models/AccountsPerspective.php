<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\AccountsPerspectiveObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;

/**
 * AccountsPerspective model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property integer $common_domain_id
 * @property integer $common_country_id
 * @property string $phone_number
 * @property string $description
 * @property integer $crm_account_id
 * @property integer $iam_account_type_id
 * @property boolean $is_paying_customer
 * @property integer $common_city_id
 * @property string $position
 * @property integer $risk_level
 * @property integer $company_size
 * @property array $sector_focus
 * @property array $industry
 * @property boolean $is_startup
 * @property array $regulatory_and_compliance
 * @property integer $employee_count
 * @property array $office_cities
 * @property string $headquarter_city
 * @property integer $production_people_count
 * @property integer $sales_people_count
 * @property integer $marketing_people_count
 * @property integer $support_people_count
 * @property integer $automation_count
 * @property string $additional_information
 * @property array $target_markets
 * @property array $partners_with
 * @property integer $iam_user_id
 * @property integer $iam_account_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AccountsPerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable;


    public $timestamps = true;

    protected $table = 'crm_accounts_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'name',
            'common_domain_id',
            'common_country_id',
            'phone_number',
            'description',
            'crm_account_id',
            'iam_account_type_id',
            'is_paying_customer',
            'common_city_id',
            'position',
            'risk_level',
            'company_size',
            'sector_focus',
            'industry',
            'is_startup',
            'regulatory_and_compliance',
            'employee_count',
            'office_cities',
            'headquarter_city',
            'production_people_count',
            'sales_people_count',
            'marketing_people_count',
            'support_people_count',
            'automation_count',
            'additional_information',
            'target_markets',
            'partners_with',
            'iam_user_id',
            'iam_account_id',
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
    'common_domain_id' => 'integer',
    'common_country_id' => 'integer',
    'phone_number' => 'string',
    'description' => 'string',
    'crm_account_id' => 'integer',
    'iam_account_type_id' => 'integer',
    'is_paying_customer' => 'boolean',
    'common_city_id' => 'integer',
    'position' => 'string',
    'risk_level' => 'integer',
    'company_size' => 'integer',
    'sector_focus' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'industry' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'is_startup' => 'boolean',
    'regulatory_and_compliance' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'employee_count' => 'integer',
    'office_cities' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'headquarter_city' => 'string',
    'production_people_count' => 'integer',
    'sales_people_count' => 'integer',
    'marketing_people_count' => 'integer',
    'support_people_count' => 'integer',
    'automation_count' => 'integer',
    'additional_information' => 'string',
    'target_markets' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'partners_with' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

    /**
     We are casting data fields.
     *
     @var array
     */
    protected $dates = [
    'created_at',
    'updated_at',
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
