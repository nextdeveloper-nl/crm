<?php

namespace NextDeveloper\CRM\Database\Models;

use Carbon\Traits\ObjectInitialisation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\Commons\Database\Traits\HasObject;
use NextDeveloper\CRM\Database\Observers\TasksObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\HasStates;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * Tasks model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $description
 * @property integer $iam_user_id
 * @property integer $crm_account_id
 * @property integer $priority
 * @property boolean $is_finished
 * @property boolean $is_delayed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property string $name
 * @property integer $iam_account_id
 * @property string $object_type
 * @property integer $object_id
 * @property \Carbon\Carbon $due_date
 * @property \Carbon\Carbon $start_date
 * @property integer $progress
 * @property integer $crm_task_id
 * @property boolean $is_milestone
 * @property integer $sort_order
 * @property float $estimated_hours
 */
class Tasks extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_tasks';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'description',
            'iam_user_id',
            'crm_account_id',
            'priority',
            'is_finished',
            'is_delayed',
            'name',
            'iam_account_id',
            'object_type',
            'object_id',
            'due_date',
            'start_date',
            'progress',
            'crm_task_id',
            'is_milestone',
            'sort_order',
            'estimated_hours',
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
    'description' => 'string',
    'crm_account_id' => 'integer',
    'priority' => 'integer',
    'is_finished' => 'boolean',
    'is_delayed' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
    'name' => 'string',
    'object_type' => 'string',
    'object_id' => 'integer',
    'due_date' => 'datetime',
    'start_date' => 'datetime',
    'progress' => 'integer',
    'crm_task_id' => 'integer',
    'is_milestone' => 'boolean',
    'sort_order' => 'integer',
    'estimated_hours' => 'double',
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
    'due_date',
    'start_date',
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
        parent::observe(TasksObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_tasks');

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

    /**
     * The task this one is a subtask of (leo4#1245).
     */
    public function parentTask(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'crm_task_id');
    }

    /**
     * Subtasks; a task with subtasks takes its dates and progress from them (leo4#1245).
     */
    public function subtasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'crm_task_id');
    }



















}
