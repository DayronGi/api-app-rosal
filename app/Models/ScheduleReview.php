<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleReview extends Model
{
    use HasFactory;

    protected $table = 'schedule_reviews';

    protected $primaryKey = 'review_id';

    public $timestamps = false;
}
