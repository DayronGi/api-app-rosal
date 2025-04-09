<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSection extends Model
{
    use HasFactory;

    protected $table = 'product_sections';

    public $timestamps = false;

    protected $primaryKey = ['product_id', 'section'];

    public $incrementing = false;

    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getAttribute($keyName));
        }

        return $query;
    }

    protected $fillable = [
        'product_id',
        'section',
        'quantity',
        'created_by',
        'creation_date',
        'status'
    ];

    public function product_id()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}