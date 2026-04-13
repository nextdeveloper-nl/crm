<?php

namespace NextDeveloper\CRM\Database\Models;

use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\CRM\Database\Observers\LeoActiveCustomersPerspectiveObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Database\Traits\HasObject;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * LeoActiveCustomersPerspective model.
 *
 * @package  NextDeveloper\CRM\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property string $phone_number
 * @property boolean $is_active
 * @property string $account_owner
 * @property string $account_owner_email
 * @property boolean $is_paying_customer
 * @property integer $risk_level
 * @property boolean $is_crm_suspended
 * @property boolean $is_crm_disabled
 * @property $accounting_balance
 * @property $accounting_credit
 * @property string $currency_code
 * @property integer $accounting_account
 * @property integer $crm_account
 * @property integer $iaas_account
 * @property integer $marketplace_account
 * @property integer $partnership_account
 * @property integer $ipaas_account
 * @property integer $intelligence_account
 * @property integer $autoquill_account
 * @property integer $llmocean_account
 * @property $total_unpaid_amount
 * @property integer $unpaid_invoice_count
 * @property integer $virtual_machine_count
 * @property integer $virtual_disk_image_count
 * @property integer $virtual_network_card_count
 * @property integer $ip_address_count
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class LeoActiveCustomersPerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;

    public $timestamps = true;

    protected $table = 'crm_leo_active_customers_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'name',
            'description',
            'phone_number',
            'is_active',
            'account_owner',
            'account_owner_email',
            'is_paying_customer',
            'risk_level',
            'is_crm_suspended',
            'is_crm_disabled',
            'accounting_balance',
            'accounting_credit',
            'currency_code',
            'accounting_account',
            'crm_account',
            'iaas_account',
            'marketplace_account',
            'partnership_account',
            'ipaas_account',
            'intelligence_account',
            'autoquill_account',
            'llmocean_account',
            'total_unpaid_amount',
            'unpaid_invoice_count',
            'virtual_machine_count',
            'virtual_disk_image_count',
            'virtual_network_card_count',
            'ip_address_count',
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
    'description' => 'string',
    'phone_number' => 'string',
    'is_active' => 'boolean',
    'account_owner' => 'string',
    'account_owner_email' => 'string',
    'is_paying_customer' => 'boolean',
    'risk_level' => 'integer',
    'is_crm_suspended' => 'boolean',
    'is_crm_disabled' => 'boolean',
    'currency_code' => 'string',
    'accounting_account' => 'integer',
    'crm_account' => 'integer',
    'iaas_account' => 'integer',
    'marketplace_account' => 'integer',
    'partnership_account' => 'integer',
    'ipaas_account' => 'integer',
    'intelligence_account' => 'integer',
    'autoquill_account' => 'integer',
    'llmocean_account' => 'integer',
    'unpaid_invoice_count' => 'integer',
    'virtual_machine_count' => 'integer',
    'virtual_disk_image_count' => 'integer',
    'virtual_network_card_count' => 'integer',
    'ip_address_count' => 'integer',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

    /**
     We are casting data fields.
     *
     @var array
     */
    protected $dates = [
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
        parent::observe(LeoActiveCustomersPerspectiveObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('crm.scopes.global');
        $modelScopes = config('crm.scopes.crm_leo_active_customers_perspective');

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
