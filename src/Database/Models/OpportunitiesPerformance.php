<?php

namespace NextDeveloper\CRM\Database\Models;

use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\OpportunitiesPerformanceObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Database\Traits\HasObject;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * OpportunitiesPerformance model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $iam_account_id
 * @property integer $leads_count
 * @property integer $prospect_count
 * @property integer $qualification_count
 * @property integer $research_count
 * @property integer $need_analysis_count
 * @property integer $approach_count
 * @property integer $value_proposition_count
 * @property integer $identifying_decision_makers_count
 * @property integer $proposal_count
 * @property integer $negotiation_count
 * @property integer $won_count
 * @property integer $lost_count
 * @property integer $cancelled_count
 * @property integer $perception_analysis_count
 * @property integer $renewal_count
 * @property string $type
 */
class OpportunitiesPerformance extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;

    public $timestamps = false;

    protected $table = 'crm_opportunities_performance';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'iam_account_id',
            'leads_count',
            'prospect_count',
            'qualification_count',
            'research_count',
            'need_analysis_count',
            'approach_count',
            'value_proposition_count',
            'identifying_decision_makers_count',
            'proposal_count',
            'negotiation_count',
            'won_count',
            'lost_count',
            'cancelled_count',
            'perception_analysis_count',
            'renewal_count',
            'type',
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
    'leads_count' => 'integer',
    'prospect_count' => 'integer',
    'qualification_count' => 'integer',
    'research_count' => 'integer',
    'need_analysis_count' => 'integer',
    'approach_count' => 'integer',
    'value_proposition_count' => 'integer',
    'identifying_decision_makers_count' => 'integer',
    'proposal_count' => 'integer',
    'negotiation_count' => 'integer',
    'won_count' => 'integer',
    'lost_count' => 'integer',
    'cancelled_count' => 'integer',
    'perception_analysis_count' => 'integer',
    'renewal_count' => 'integer',
    'type' => 'string',
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
        parent::observe(OpportunitiesPerformanceObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_opportunities_performance');

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
