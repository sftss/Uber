<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = "menu";
    protected $primaryKey = "id_menu";
    public $timestamps = false;

    protected $fillable = [
        'id_restaurant',
        'libelle_menu',
        'prix_menu',
        'photo_menu',        
    ];
}
