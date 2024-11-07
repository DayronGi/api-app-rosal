<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends Model
{
    use HasFactory;

    protected $primaryKey = 'license_id';

    public $timestamps = false;

    protected $table = 'licenses';

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'user_data_id');
    }

    public function creation(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'created_by', 'user_id');
    }

}
