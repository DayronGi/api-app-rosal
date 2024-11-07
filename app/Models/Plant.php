<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plant extends Model
{
    use HasFactory;

    protected $table = 'plants';

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'plant_id', 'plant_id');
    }
}
