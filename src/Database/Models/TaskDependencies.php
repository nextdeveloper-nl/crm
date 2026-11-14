<?php

namespace NextDeveloper\CRM\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A link between two tasks: the successor waits for the predecessor (leo4#1245).
 *
 * Hand-written rather than generated: it is only read and written through TaskScheduleService,
 * which checks that both tasks belong to the current account and that the user may edit the
 * successor, so it carries no observer or global scopes of its own.
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $predecessor_task_id
 * @property integer $successor_task_id
 * @property string $type
 * @property integer $lag_days
 * @property integer $iam_account_id
 * @property integer $iam_user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class TaskDependencies extends Model
{
    use SoftDeletes;

    protected $table = 'crm_task_dependencies';

    protected $fillable = [
        'predecessor_task_id',
        'successor_task_id',
        'type',
        'lag_days',
        'iam_account_id',
        'iam_user_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'predecessor_task_id' => 'integer',
        'successor_task_id' => 'integer',
        'lag_days' => 'integer',
        'iam_account_id' => 'integer',
        'iam_user_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function predecessor(): BelongsTo
    {
        return $this->belongsTo(Tasks::class, 'predecessor_task_id');
    }

    public function successor(): BelongsTo
    {
        return $this->belongsTo(Tasks::class, 'successor_task_id');
    }
}
