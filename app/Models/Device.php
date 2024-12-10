<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    // Define el nombre de la tabla si no sigue el estándar plural de Eloquent
    protected $table = 'devices';

    // Campos que pueden ser llenados de forma masiva
    protected $fillable = [
        'uid',
        'pwd',
        'user_id',
        'status',
    ];

    // Si tu tabla no usa timestamps (created_at, updated_at), desactívalos
    public $timestamps = false;
}
