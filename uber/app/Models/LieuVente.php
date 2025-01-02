<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LieuVente extends Model
{
    protected $table = "lieu_de_vente_pf";
    protected $primaryKey = "id_lieu_de_vente_pf";
    protected $fillable = ['id_adresse', 'id_proprietaire', 'nom_etablissement', 'description_etablissement', 'propose_livraison', 'photo_lieu'];

    public $timestamps = false;
    
    public function horaires() {
        return $this->hasMany(HorairesLieuVente::class, 'id_lieu_de_vente_pf', 'id_lieu_de_vente_pf');
    }

    public function getHorairesFormatte() {
        return $this->horaires->map(function ($horaire) {
            if ($horaire->ferme) {
                return ['jour' => $horaire->jour->lib_jour, 'ferme' => true];
            }
            return [
                'jour' => $horaire->jour->lib_jour,
                'ouverture' => $horaire->horaires_ouverture,
                'fermeture' => $horaire->horaires_fermeture,
                'ferme' => false,
            ];
        });
    }

    public function adresse() {
        return $this->belongsTo(Adresse::class, 'id_adresse', 'id_adresse');
    }
}
