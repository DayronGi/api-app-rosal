<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignedTask extends Model
{
    use HasFactory;

    protected $primaryKey = 'task_id';

    protected $table = 'assigned_tasks';

    protected $fillable = [
        'meeting_id',
        'start_date',
        'estimated_time',
        'units',
        'type_id',
        'task_description',
        'creation_date',
        'assigned_to',
        'department_id',
        'observations',
        'score',
        'priority',
        'created_by',
        'reviewed_by',
        'status',
    ];
    public $timestamps = false;

    public function creation(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'created_by', 'user_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'assigned_to', 'user_data_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TaskType::class, 'type_id', 'type_id');
    }
}
