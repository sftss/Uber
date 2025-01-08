<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComposeDe extends Model
{
    use HasFactory;
    protected $table = 'compose_de';
    protected $primaryKey = ['id_menu', 'id_plat'];
    public $incrementing = false;

    protected $fillable = [
        'id_menu',
        'id_plat',
    ];

    public $timestamps = false;
}
