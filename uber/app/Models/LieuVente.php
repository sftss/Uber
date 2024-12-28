<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LieuVente extends Model
{
    protected $table = "lieu_de_vente_pf";
    protected $primaryKey = "id_lieu_de_vente_pf";
    protected $fillable = ['id_adresse', 'nom_etablissement', 'horaires_ouverture', 'horaires_fermeture', 'description_etablissement', 'propose_livraison', 'photo_lieu'];

    public $timestamps = false;
}
