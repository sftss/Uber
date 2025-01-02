<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produit'; 
    protected $primaryKey = 'id_produit'; 
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
