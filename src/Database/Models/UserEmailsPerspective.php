<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\UserEmailsPerspectiveObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Database\Traits\HasObject;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * UserEmailsPerspective model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $position
 * @property string $job
 * @property array $crm_tags
 * @property boolean $is_suspended
 * @property integer $iam_user_id
 * @property string $fullname
 * @property string $email
 * @property string $phone_number
 * @property integer $communication_email_id
 * @property string $communication_email_uuid
 * @property string $from_email_address
 * @property string $subject
 * @property string $body
 * @property boolean $is_marketing_email
 * @property \Carbon\Carbon $deliver_at
 * @property \Carbon\Carbon $delivered_at
 * @property integer $iam_account_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class UserEmailsPerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_user_emails_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'position',
            'job',
            'crm_tags',
            'is_suspended',
            'iam_user_id',
            'fullname',
            'email',
            'phone_number',
            'communication_email_id',
            'communication_email_uuid',
            'from_email_address',
            'subject',
            'body',
            'is_marketing_email',
            'deliver_at',
            'delivered_at',
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
    'position' => 'string',
    'job' => 'string',
    'crm_tags' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'is_suspended' => 'boolean',
    'fullname' => 'string',
    'email' => 'string',
    'phone_number' => 'string',
    'communication_email_id' => 'integer',
    'from_email_address' => 'string',
    'subject' => 'string',
    'body' => 'string',
    'is_marketing_email' => 'boolean',
    'deliver_at' => 'datetime',
    'delivered_at' => 'datetime',
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
    'deliver_at',
    'delivered_at',
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
        parent::observe(UserEmailsPerspectiveObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_user_emails_perspective');

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
