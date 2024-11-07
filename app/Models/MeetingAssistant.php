<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingAssistant extends Model
{
    use HasFactory;

    protected $table = 'meeting_assistants';

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'user_data_id');
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'meeting_id', 'meeting_id');
    }

}
