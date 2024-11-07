<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'meetings';

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'called_by', 'user_data_id');
    }

    public function creation(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'created_by', 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function assistants(): HasMany
    {
        return $this->hasMany(MeetingAssistant::class, 'meeting_id', 'meeting_id');
    }

    public function meeting_topics(): HasMany
    {
        return $this->hasMany(MeetingTopic::class, 'meeting_id', 'meeting_id');
    }
}
