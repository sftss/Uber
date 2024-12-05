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
        'id_course','id_chauffeur','id_lieu_depart','id_lieu_arrivee','id_client','prix_reservation','date_prise_en_charge','duree_course','temps_arrivee','id_velo'
    ];
}
