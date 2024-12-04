<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = "course";
    protected $primaryKey = "id_course";
    public $timestamps = false;

    // Ajoutez ici toutes les colonnes qui peuvent être assignées en masse
    protected $fillable = [
        'ID_COURSE', 'ID_CHAUFFEUR', 'ID_VELO', 'ID_LIEU_DEPART', 'ID_LIEU_ARRIVEE', 
        'ID_CLIENT', 'PRIX_RESERVATION', 'DATE_PRISE_EN_CHARGE', 'DUREE_COURSE', 'LONGUEUR_COURSE', 
        'TEMPS_ARRIVEE'
    ];
}
