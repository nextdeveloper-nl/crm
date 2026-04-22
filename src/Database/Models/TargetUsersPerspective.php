<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\TargetUsersPerspectiveObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;
use NextDeveloper\Commons\Database\Traits\HasObject;

/**
 * TargetUsersPerspective model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $pronoun
 * @property string $name
 * @property string $surname
 * @property string $fullname
 * @property \Carbon\Carbon $birthday
 * @property string $email
 * @property string $phone_number
 * @property integer $common_country_id
 * @property integer $common_language_id
 * @property array $user_tags
 * @property string $about
 * @property integer $crm_user_id
 * @property string $position
 * @property string $job
 * @property string $job_description
 * @property string $hobbies
 * @property string $city
 * @property string $relationship_status
 * @property boolean $is_evangelist
 * @property boolean $is_single
 * @property $education_level
 * @property integer $child_count
 * @property array $crm_tags
 * @property boolean $is_suspended
 * @property integer $crm_target_id
 * @property string $target_name
 * @property string $target_description
 * @property integer $iam_account_id
 * @property integer $iam_user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class TargetUsersPerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;

    public $timestamps = true;

    protected $table = 'crm_target_users_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'pronoun',
            'name',
            'surname',
            'fullname',
            'birthday',
            'email',
            'phone_number',
            'common_country_id',
            'common_language_id',
            'user_tags',
            'about',
            'crm_user_id',
            'position',
            'job',
            'job_description',
            'hobbies',
            'city',
            'relationship_status',
            'is_evangelist',
            'is_single',
            'education_level',
            'child_count',
            'crm_tags',
            'is_suspended',
            'crm_target_id',
            'target_name',
            'target_description',
            'iam_account_id',
            'iam_user_id',
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
    'pronoun' => 'string',
    'name' => 'string',
    'surname' => 'string',
    'fullname' => 'string',
    'birthday' => 'datetime',
    'email' => 'string',
    'phone_number' => 'string',
    'common_country_id' => 'integer',
    'common_language_id' => 'integer',
    'user_tags' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'about' => 'string',
    'crm_user_id' => 'integer',
    'position' => 'string',
    'job' => 'string',
    'job_description' => 'string',
    'hobbies' => 'string',
    'city' => 'string',
    'relationship_status' => 'string',
    'is_evangelist' => 'boolean',
    'is_single' => 'boolean',
    'child_count' => 'integer',
    'crm_tags' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'is_suspended' => 'boolean',
    'crm_target_id' => 'integer',
    'target_name' => 'string',
    'target_description' => 'string',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

    /**
     We are casting data fields.
     *
     @var array
     */
    protected $dates = [
    'birthday',
    'created_at',
    'updated_at',
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
        parent::observe(TargetUsersPerspectiveObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_target_users_perspective');

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
