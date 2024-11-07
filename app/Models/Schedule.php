<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedule';

    protected $primaryKey = 'schedule_id';

    public $timestamps = false;

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function scheduledWorkers()
    {
        return $this->hasMany(ScheduledWorker::class, 'schedule_id', 'schedule_id');
    }
}
