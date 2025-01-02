<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = "restaurant";
    protected $primaryKey = "id_restaurant";
    public $timestamps = false;

    protected $fillable = [
        'id_proprietaire',
        'id_adresse',     
        'nom_etablissement',
        'description_etablissement',
        'propose_livraison',
        'propose_retrait',
        'photo_restaurant',
    ];

    public function horaires() {
        return $this->hasMany(HorairesRestaurant::class, 'id_restaurant', 'id_restaurant');
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
}
