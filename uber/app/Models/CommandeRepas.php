<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeRepas extends Model
{
    use HasFactory;
    protected $table = "commande_repas";
     protected $primaryKey = "id_commande_repas";
     public $timestamps = false;

     protected $fillable = [
        'id_lieu_de_vente_pf', 'id_restaurant', 'id_chauffeur', 'id_client', 'id_adresse', 
        'type_livraison', 'horaire_livraison', 'temps_de_livraison'
    ];
}
