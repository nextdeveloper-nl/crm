<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\IdealCustomerProfilesPerspectiveObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;

/**
 * IdealCustomerProfilesPerspective model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property string $company_size
 * @property boolean $is_working_home_office
 * @property array $current_technology_stack
 * @property string $additional_notes
 * @property string $growth_stage
 * @property array $geographical_focus
 * @property string $business_model
 * @property array $verticals
 * @property integer $technology_rank
 * @property array $keywords
 * @property integer $opportunity_count
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class IdealCustomerProfilesPerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_ideal_customer_profiles_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'name',
            'description',
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
            'opportunity_count',
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
    'description' => 'string',
    'company_size' => 'string',
    'is_working_home_office' => 'boolean',
    'current_technology_stack' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'additional_notes' => 'string',
    'growth_stage' => 'string',
    'geographical_focus' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'business_model' => 'string',
    'verticals' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'technology_rank' => 'integer',
    'keywords' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'opportunity_count' => 'integer',
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
        parent::observe(IdealCustomerProfilesPerspectiveObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_ideal_customer_profiles_perspective');

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
