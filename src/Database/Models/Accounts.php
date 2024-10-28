<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\AccountsObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\HasStates;

/**
 * Accounts model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property integer $iam_account_id
 * @property boolean $is_paying_customer
 * @property integer $risk_level
 * @property integer $common_city_id
 * @property string $position
 * @property array $tags
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
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
 * @property array $services
 * @property boolean $is_suspended
 * @property boolean $is_service_enabled
 */
class Accounts extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_accounts';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'iam_account_id',
            'is_paying_customer',
            'risk_level',
            'common_city_id',
            'position',
            'tags',
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
            'services',
            'is_suspended',
            'is_service_enabled',
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
    'is_paying_customer' => 'boolean',
    'risk_level' => 'integer',
    'common_city_id' => 'integer',
    'position' => 'string',
    'tags' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
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
    'services' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'is_suspended' => 'boolean',
    'is_service_enabled' => 'boolean',
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
        parent::observe(AccountsObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_accounts');

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

    public function accountManagers() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\NextDeveloper\CRM\Database\Models\AccountManagers::class);
    }

    public function accounts() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\NextDeveloper\IAM\Database\Models\Accounts::class);
    }
    
    public function cities() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\NextDeveloper\Commons\Database\Models\Cities::class);
    }
    
    public function opportunities() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\NextDeveloper\CRM\Database\Models\Opportunities::class);
    }

    public function projects() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\NextDeveloper\CRM\Database\Models\Projects::class);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE





}
