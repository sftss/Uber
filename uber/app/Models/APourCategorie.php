<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class APourCategorie extends Model
{
    use HasFactory;

    protected $table = "a_pour_categorie";
    protected $primaryKey = ['id_restaurant', 'id_categorie'];
    protected $fillable = ['id_restaurant', 'id_categorie'];
    
    public $timestamps = false;
}