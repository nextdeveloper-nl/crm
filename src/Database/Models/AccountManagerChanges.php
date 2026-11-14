<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Database\Traits\UuidId;

/**
 * Audit trail of account responsible changes (leo4#1237): who added, removed, changed or reassigned a
 * responsible, with the row state before and after. The rows of one bulk reassign share a batch_uuid so the
 * reassign can be undone.
 *
 * Hand-written rather than generated: it is only written and read by the responsibles service in leo4, which
 * checks the caller's role and the account's visibility, so it carries no observer or global scopes of its own.
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $crm_account_manager_id
 * @property integer $crm_account_id
 * @property integer $responsible_user_id
 * @property string $action
 * @property array $before
 * @property array $after
 * @property string $batch_uuid
 * @property boolean $is_reverted
 * @property integer $iam_user_id
 * @property integer $iam_account_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class AccountManagerChanges extends Model
{
    use SoftDeletes, UuidId;

    public const ACTION_ADDED = 'added';

    public const ACTION_REMOVED = 'removed';

    public const ACTION_ROLE_CHANGED = 'role_changed';

    public const ACTION_PRIMARY_CHANGED = 'primary_changed';

    public const ACTION_REASSIGNED = 'reassigned';

    public const ACTION_REVERTED = 'reverted';

    protected $table = 'crm_account_manager_changes';

    protected $fillable = [
        'crm_account_manager_id',
        'crm_account_id',
        'responsible_user_id',
        'action',
        'before',
        'after',
        'batch_uuid',
        'is_reverted',
        'iam_user_id',
        'iam_account_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'crm_account_manager_id' => 'integer',
        'crm_account_id' => 'integer',
        'responsible_user_id' => 'integer',
        'before' => 'array',
        'after' => 'array',
        'is_reverted' => 'boolean',
        'iam_user_id' => 'integer',
        'iam_account_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function accountManager(): BelongsTo
    {
        return $this->belongsTo(AccountManagers::class, 'crm_account_manager_id')->withTrashed();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class, 'crm_account_id');
    }
}
