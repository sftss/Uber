<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Adresse;

class Chauffeur extends Model
{
    use HasFactory;

    protected $table = "chauffeur";
    protected $primaryKey = "id_chauffeur";
    public $timestamps = false;

    public function adresse(): HasOne{
        return $this->hasOne(
            Adresse::class, 
            "id_adresse", 
            "id_adresse_actuelle");
    }

    public function vehicule(): HasOne
    {
        return $this->hasOne(
            Vehicule::class,
            "id_chauffeur",
            "id_chauffeur"
        );
    }
}
