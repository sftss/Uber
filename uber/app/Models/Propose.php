<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propose extends Model
{
    use HasFactory;
    protected $table = 'propose';
    protected $primaryKey = ['id_restaurant', 'id_plat'];
    public $incrementing = false;

    protected $fillable = [
        'id_restaurant',
        'id_plat',
    ];

    public $timestamps = false;
}
