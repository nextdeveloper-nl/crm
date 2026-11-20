<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\AccountContactsObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\HasStates;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;
use NextDeveloper\Commons\Database\Traits\HasObject;

/**
 * AccountContacts model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property integer $crm_account_id
 * @property integer $agenda_contact_id
 * @property string $role
 * @property boolean $is_primary
 * @property string $visibility private|team|specific
 * @property array $data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property integer $iam_account_id
 * @property integer $iam_user_id
 */
class AccountContacts extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_account_contacts';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'crm_account_id',
        'agenda_contact_id',
        'role',
        'is_primary',
        'visibility',
        'data',
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
        'crm_account_id' => 'integer',
        'agenda_contact_id' => 'integer',
        'role' => 'string',
        'is_primary' => 'boolean',
        'visibility' => 'string',
        'data' => 'array',
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
        parent::observe(AccountContactsObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_account_contacts');

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

    // Visibility levels of a contact link (who besides the owner may see the contact through this CRM account).
    public const VISIBILITY_PRIVATE = 'private';
    public const VISIBILITY_TEAM = 'team';
    public const VISIBILITY_SPECIFIC = 'specific';

    public const VISIBILITIES = [self::VISIBILITY_PRIVATE, self::VISIBILITY_TEAM, self::VISIBILITY_SPECIFIC];
}
