<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\MeetingsObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\HasStates;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * Meetings model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property integer $agenda_calendar_item_id
 * @property string $meeting_note
 * @property string $outcome
 * @property integer $iam_user_id
 * @property integer $iam_account_id
 * @property integer $crm_account_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property string $customer_requirements
 * @property string $suggestions
 * @property integer $crm_opportunity_id
 */
class Meetings extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_meetings';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'agenda_calendar_item_id',
            'meeting_note',
            'outcome',
            'iam_user_id',
            'iam_account_id',
            'crm_account_id',
            'customer_requirements',
            'suggestions',
            'crm_opportunity_id',
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
    'agenda_calendar_item_id' => 'integer',
    'meeting_note' => 'string',
    'outcome' => 'string',
    'crm_account_id' => 'integer',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
    'customer_requirements' => 'string',
    'suggestions' => 'string',
    'crm_opportunity_id' => 'integer',
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
        parent::observe(MeetingsObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_meetings');

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

    public function opportunities() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\NextDeveloper\CRM\Database\Models\Opportunities::class);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
















































}
