<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chauffeur extends Authenticatable
{
    use HasFactory;

    protected $table = "chauffeur";
    protected $primaryKey = "id_chauffeur";
    public $timestamps = false;

    public function adresse(): HasOne {
        return $this->hasOne(
            Adresse::class, 
            "id_adresse", 
            "id_adresse_actuelle"
        );
    }

    public function vehicule(): HasOne {
        return $this->hasOne(
            Vehicule::class,
            "id_chauffeur",
            "id_chauffeur"
        );
    }

    protected $fillable = [
        'id_chauffeur', 'id_sd', 'photo', 'tel_chauffeur', 'mail_chauffeur', 
        'num_siret', 'sexe_chauffeur', 'prenom_chauffeur', 'nom_chauffeur', 'date_naissance_chauffeur', 
        'mdp_chauffeur', 'newsletter'
    ];

    protected $hidden = [
        'mdp_chauffeur', 
    ];
}
