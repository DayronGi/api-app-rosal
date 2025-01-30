<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $casts = [
        'day' => 'date',
    ];

    public $timestamps = false;

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'user_data_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id', 'job_id');
    }

    public function creation(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'created_by', 'user_id');
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(PlantProduct::class, 'product_id', 'plant_id');
    }

}
