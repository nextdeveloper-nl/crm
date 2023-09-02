<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\CrmOpportunityObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;

/**
* Class CrmOpportunity.
*
* @package NextDeveloper\CRM\Database\Models
*/
class CrmOpportunity extends Model
{
use Filterable, UuidId;
	use SoftDeletes;


	public $timestamps = true;

protected $table = 'crm_opportunities';


/**
* @var array
*/
protected $guarded = [];

/**
*  Here we have the fulltext fields. We can use these for fulltext search if enabled.
*/
protected $fullTextFields = [

];

/**
* @var array
*/
protected $appends = [

];

/**
* We are casting fields to objects so that we can work on them better
* @var array
*/
protected $casts = [
'id'             => 'integer',
		'uuid'           => 'string',
		'name'           => 'string',
		'phone_number'   => 'string',
		'description'    => 'string',
		'probability'    => 'boolean',
		'source'         => 'string',
		'income'         => 'integer',
		'deadline'       => 'datetime',
		'iam_account_id' => 'integer',
		'created_at'     => 'datetime',
		'updated_at'     => 'datetime',
		'deleted_at'     => 'datetime',
];

/**
* We are casting data fields.
* @var array
*/
protected $dates = [
'deadline',
		'created_at',
		'updated_at',
		'deleted_at',
];

/**
* @var array
*/
protected $with = [

];

/**
* @var int
*/
protected $perPage = 20;

/**
* @return void
*/
public static function boot()
{
parent::boot();

//  We create and add Observer even if we wont use it.
parent::observe(CrmOpportunityObserver::class);

self::registerScopes();
}

public static function registerScopes()
{
$globalScopes = config('crm.scopes.global');
$modelScopes = config('crm.scopes.crm_opportunities');

if(!$modelScopes) $modelScopes = [];
if (!$globalScopes) $globalScopes = [];

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

// EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n
}