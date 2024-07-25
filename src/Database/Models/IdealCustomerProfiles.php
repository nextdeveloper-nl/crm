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
 * @property string $customer_positions
 * @property string $company_size
 * @property boolean $is_working_home_office
 * @property array $pain_points
 * @property string $solutions_interested_in
 * @property string $current_technology_stack
 * @property $budget
 * @property string $decision_making_process
 * @property string $implementation_timeline
 * @property string $unique_selling_proposition
 * @property string $lead_generation_channels
 * @property string $sales_process
 * @property string $sales_funnel
 * @property string $additional_notes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
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
            'customer_positions',
            'company_size',
            'is_working_home_office',
            'pain_points',
            'solutions_interested_in',
            'current_technology_stack',
            'budget',
            'decision_making_process',
            'implementation_timeline',
            'unique_selling_proposition',
            'lead_generation_channels',
            'sales_process',
            'sales_funnel',
            'additional_notes',
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
    'customer_positions' => 'string',
    'company_size' => 'string',
    'is_working_home_office' => 'boolean',
    'pain_points' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'solutions_interested_in' => 'string',
    'current_technology_stack' => 'string',
    'decision_making_process' => 'string',
    'implementation_timeline' => 'string',
    'unique_selling_proposition' => 'string',
    'lead_generation_channels' => 'string',
    'sales_process' => 'string',
    'sales_funnel' => 'string',
    'additional_notes' => 'string',
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
