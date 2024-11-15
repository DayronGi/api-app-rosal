<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Handbook extends Model
{
    use HasFactory;

    protected $table = 'handbooks';

    public function creation(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'created_by', 'user_id');
    }

    public function updateByUser(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'updated_by', 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
