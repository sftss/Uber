<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    use HasFactory;
    protected $table = "plat";
    protected $primaryKey = "id_plat";
    public $timestamps = false;

    protected $fillable = [
        'id_categorie_produit',
        'libelle_plat',
        'prix_plat',
        'note_plat',        
        'nb_avis',        
        'photo_plat',        
    ];
}
