<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\AccountManagersObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\HasStates;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;
use NextDeveloper\Commons\Database\Traits\HasObject;

/**
 * AccountManagers model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property integer $crm_account_id
 * @property integer $iam_user_id
 * @property integer $iam_account_id
 * @property string $role
 * @property boolean $is_primary
 * @property integer $created_by
 * @property integer $updated_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class AccountManagers extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_account_managers';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'crm_account_id',
            'iam_user_id',
            'iam_account_id',
            'role',
            'is_primary',
            'created_by',
            'updated_by',
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
    'role' => 'string',
    'is_primary' => 'boolean',
    'created_by' => 'integer',
    'updated_by' => 'integer',
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
        parent::observe(AccountManagersObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_account_managers');

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
     * Looks after the customer. The only role that grants the module *-success-manager roles on the customer's
     * IAM account (leo4#1237).
     */
    public const ROLE_SUCCESS_MANAGER = 'success_manager';

    public const ROLE_SALES = 'sales';

    public const ROLE_SUPPORT = 'support';

    public const ROLE_TECHNICAL = 'technical';

    /**
     * The responsible roles a row may hold. Mirrors crm_account_managers_role_check.
     */
    public const ROLES = [
        self::ROLE_SUCCESS_MANAGER,
        self::ROLE_SALES,
        self::ROLE_SUPPORT,
        self::ROLE_TECHNICAL,
    ];








































































}
