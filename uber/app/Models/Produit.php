<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produit'; // Nom de la table
    protected $primaryKey = 'id_produit';  // Remplacez par le nom de votre colonne clé primaire



    // Indique si les colonnes `created_at` et `updated_at` doivent être gérées automatiquement
    public $timestamps = false;


    protected $fillable = [
        'id_categorie_produit',
        'nom_produit',
        'note_produit',
        'nb_avis',        
        'prix_produit',        
        'photo_produit',        
    ];
}
