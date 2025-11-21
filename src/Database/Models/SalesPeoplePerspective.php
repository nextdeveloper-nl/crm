<?php

namespace NextDeveloper\CRM\Database\Models;

use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\SalesPeoplePerspectiveObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;
use NextDeveloper\Commons\Database\Traits\HasObject;

/**
 * SalesPeoplePerspective model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $surname
 * @property string $fullname
 * @property string $email
 * @property string $about
 * @property string $pronoun
 * @property \Carbon\Carbon $birthday
 * @property string $nin
 * @property integer $common_country_id
 * @property string $country
 * @property integer $common_language_id
 * @property string $language
 * @property \Carbon\Carbon $iam_updated_at
 * @property string $phone_number
 * @property boolean $is_registered
 * @property boolean $is_nin_verified
 * @property boolean $is_email_verified
 * @property boolean $is_phone_number_verified
 * @property integer $profile_picture_identity
 * @property string $position
 * @property string $job
 * @property string $job_description
 * @property string $hobbies
 * @property string $city
 * @property $email_risk
 * @property string $relationship_status
 * @property boolean $is_evangelist
 * @property boolean $is_single
 * @property $education
 * @property integer $child_count
 * @property integer $iam_user_id
 * @property integer $iam_account_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SalesPeoplePerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;

    public $timestamps = true;

    protected $table = 'crm_sales_people_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'name',
            'surname',
            'fullname',
            'email',
            'about',
            'pronoun',
            'birthday',
            'nin',
            'common_country_id',
            'country',
            'common_language_id',
            'language',
            'iam_updated_at',
            'phone_number',
            'is_registered',
            'is_nin_verified',
            'is_email_verified',
            'is_phone_number_verified',
            'profile_picture_identity',
            'position',
            'job',
            'job_description',
            'hobbies',
            'city',
            'email_risk',
            'relationship_status',
            'is_evangelist',
            'is_single',
            'education',
            'child_count',
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
    'surname' => 'string',
    'fullname' => 'string',
    'email' => 'string',
    'about' => 'string',
    'pronoun' => 'string',
    'birthday' => 'datetime',
    'nin' => 'string',
    'common_country_id' => 'integer',
    'country' => 'string',
    'common_language_id' => 'integer',
    'language' => 'string',
    'iam_updated_at' => 'datetime',
    'phone_number' => 'string',
    'is_registered' => 'boolean',
    'is_nin_verified' => 'boolean',
    'is_email_verified' => 'boolean',
    'is_phone_number_verified' => 'boolean',
    'profile_picture_identity' => 'integer',
    'position' => 'string',
    'job' => 'string',
    'job_description' => 'string',
    'hobbies' => 'string',
    'city' => 'string',
    'relationship_status' => 'string',
    'is_evangelist' => 'boolean',
    'is_single' => 'boolean',
    'child_count' => 'integer',
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
    'iam_updated_at',
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
        parent::observe(SalesPeoplePerspectiveObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_sales_people_perspective');

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
