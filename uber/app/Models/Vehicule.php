<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Vehicule extends Model
{
    use HasFactory;
    protected $table = "vehicule";
    protected $primaryKey = "id_vehicule";
    public $timestamps = false;

    public function categorieVehicule(): HasOne
    {
        return $this->hasOne(
            CategorieVehicule::class,
            "id_categorie_vehicule",
            "id_categorie_vehicule"
        );
    }
}
