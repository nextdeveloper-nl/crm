<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\AccountCommunicationsPerspectiveObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Database\Traits\HasObject;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * AccountCommunicationsPerspective model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property integer $crm_account_id
 * @property string $crm_account_uuid
 * @property integer $direction
 * @property string $from_email_address
 * @property string $recipient
 * @property string $subject
 * @property string $body
 * @property string $content_type
 * @property boolean $is_marketing_email
 * @property string $message_status
 * @property \Carbon\Carbon $deliver_at
 * @property \Carbon\Carbon $delivered_at
 * @property \Carbon\Carbon $read_at
 * @property \Carbon\Carbon $failed_at
 * @property string $failure_reason
 * @property integer $iam_account_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class AccountCommunicationsPerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_account_communications_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'crm_account_id',
            'direction',
            'from_email_address',
            'recipient',
            'subject',
            'body',
            'content_type',
            'is_marketing_email',
            'message_status',
            'deliver_at',
            'delivered_at',
            'read_at',
            'failed_at',
            'failure_reason',
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
    'direction' => 'integer',
    'from_email_address' => 'string',
    'recipient' => 'string',
    'subject' => 'string',
    'body' => 'string',
    'content_type' => 'string',
    'is_marketing_email' => 'boolean',
    'message_status' => 'string',
    'deliver_at' => 'datetime',
    'delivered_at' => 'datetime',
    'read_at' => 'datetime',
    'failed_at' => 'datetime',
    'failure_reason' => 'string',
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
    'read_at',
    'failed_at',
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
        parent::observe(AccountCommunicationsPerspectiveObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_account_communications_perspective');

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
