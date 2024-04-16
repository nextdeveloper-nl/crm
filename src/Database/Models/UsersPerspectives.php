<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\UsersPerspectivesObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;

/**
 * Class UsersPerspectives.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class UsersPerspectives extends Model
{
    use Filterable, UuidId, CleanCache, Taggable;


    public $timestamps = true;

    protected $table = 'crm_users_perspective';


    /**
     @var array
     */
    protected $guarded = [];

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
    'id'                 => 'integer',
    'uuid'               => 'string',
    'name'               => 'string',
    'surname'            => 'string',
    'fullname'           => 'string',
    'email'              => 'string',
    'about'              => 'string',
    'pronoun'            => 'string',
    'birthday'           => 'datetime',
    'nin'                => 'string',
    'cell_phone'         => 'string',
    'common_country_id'  => 'integer',
    'common_language_id' => 'integer',
    'iam_updated_at'     => 'datetime',
    'position'           => 'string',
    'job_description'    => 'string',
    'hobbies'            => 'string',
    'city'               => 'string',
    'is_evangelist'      => 'boolean',
    'child_count'        => 'integer',
    'created_at'         => 'datetime',
    'updated_at'         => 'datetime',
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
        parent::observe(UsersPerspectivesObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_users_perspective');

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
