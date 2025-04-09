<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMovement extends Model
{
    use HasFactory;

    protected $table = 'product_movements';

    public $timestamps = false;

    protected $primaryKey = 'movement_id';

    protected $fillable = [
        'movement_type',
        'movement_date',
        'section',
        'income',
        'outcome',
        'details',
        'created_by',
        'creation_date',
        'updated_by',
        'update_date',
        'status'
    ];

    public function product_id() {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function updated_by() {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');
    }

    public function created_by() {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}