<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposeMenu extends Model
{
    use HasFactory;
    protected $table = 'propose_menu';
    protected $primaryKey = ['id_restaurant', 'id_menu'];
    public $incrementing = false;

    protected $fillable = [
        'id_restaurant',
        'id_menu',
    ];

    public $timestamps = false;
}
