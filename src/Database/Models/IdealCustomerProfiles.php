<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\IdealCustomerProfilesObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\HasStates;

/**
 * IdealCustomerProfiles model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property integer $crm_account_id
 * @property string $company_size
 * @property boolean $is_working_home_office
 * @property array $current_technology_stack
 * @property string $additional_notes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property string $growth_stage
 * @property array $geographical_focus
 * @property string $business_model
 * @property array $verticals
 * @property integer $technology_rank
 * @property array $keywords
 * @property string $name
 * @property string $description
 */
class IdealCustomerProfiles extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_ideal_customer_profiles';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'crm_account_id',
            'company_size',
            'is_working_home_office',
            'current_technology_stack',
            'additional_notes',
            'growth_stage',
            'geographical_focus',
            'business_model',
            'verticals',
            'technology_rank',
            'keywords',
            'name',
            'description',
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
    'crm_account_id' => 'integer',
    'company_size' => 'string',
    'is_working_home_office' => 'boolean',
    'current_technology_stack' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'additional_notes' => 'string',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
    'growth_stage' => 'string',
    'geographical_focus' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'business_model' => 'string',
    'verticals' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'technology_rank' => 'integer',
    'keywords' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'name' => 'string',
    'description' => 'string',
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
        parent::observe(IdealCustomerProfilesObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_ideal_customer_profiles');

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
