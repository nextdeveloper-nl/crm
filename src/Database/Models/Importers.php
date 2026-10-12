<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\ImportersObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\HasStates;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;
use NextDeveloper\Commons\Database\Traits\HasObject;

/**
 * Importers model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $source_type
 * @property string $status
 * @property array $config
 * @property string $credentials_enc
 * @property string $cursor
 * @property \Carbon\Carbon $last_synced_at
 * @property string $last_error
 * @property string $object_type
 * @property integer $object_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property integer $iam_user_id
 * @property integer $iam_account_id
 */
class Importers extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'crm_importers';


    /**
     @var array
     */
    protected $guarded = [];

    /**
     * Credentials are write-only; never serialized.
     */
    protected $hidden = ['credentials_enc'];

    protected $fillable = [
            'name',
            'source_type',
            'status',
            'config',
            'credentials_enc',
            'cursor',
            'last_synced_at',
            'last_error',
            'object_type',
            'object_id',
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
    'name' => 'string',
    'source_type' => 'string',
    'status' => 'string',
    'config' => 'array',
    'credentials_enc' => 'string',
    'cursor' => 'string',
    'last_synced_at' => 'datetime',
    'last_error' => 'string',
    'object_type' => 'string',
    'object_id' => 'integer',
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
        parent::observe(ImportersObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_importers');

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
     * Encrypt the source credentials on write (same pattern as Marketplace Providers).
     * The column is also in $hidden, so it never leaves through toArray()/API responses.
     */
    public function setCredentialsEncAttribute($value): void
    {
        $this->attributes['credentials_enc'] = $value === null || $value === ''
            ? null
            : encrypt(is_array($value) ? json_encode($value) : $value);
    }

    /**
     * Decrypted source credentials, or null when none are stored or decrypting fails
     * (a bad APP_KEY must not break a worker looping over every importer).
     */
    public function getDecryptedCredentials(): ?string
    {
        if (empty($this->attributes['credentials_enc'])) {
            return null;
        }

        try {
            return decrypt($this->attributes['credentials_enc']);
        } catch (\Throwable $e) {
            return null;
        }
    }







































































}
