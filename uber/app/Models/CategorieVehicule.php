<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Vehicule;

class CategorieVehicule extends Model
{
    use HasFactory;

    protected $table = "categorie_vehicule";
    protected $primaryKey = "id_categorie_vehicule";
    public $timestamps = false;
}
