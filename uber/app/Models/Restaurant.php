<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $table = "restaurant";
    protected $primaryKey = "id_restaurant";
    public $timestamps = false;

    protected $fillable = [
        'nom_etablissement',
        'horaires_ouverture',
        'horaires_fermeture',
        'description_etablissement',
        'propose_livraison',
        'propose_retrait',
        'photo_restaurant',
        'id_proprietaire',
        'id_adresse',
        
    ];
}
