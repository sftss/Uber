<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = "course";
    protected $primaryKey = "id_course";
    public $timestamps = false;

    public function index()
    {
        // Récupérer toutes les courses
        $courses = Course::all();

        return view('courses.index', compact('courses'));
    }

    public function lieuDepart()
    {
        return $this->belongsTo(Adresse::class, 'id_lieu_depart', 'id_adresse');
    }

    public function lieuArrivee()
    {
        return $this->belongsTo(Adresse::class, 'id_lieu_arrivee', 'id_adresse');
    }

    // Ajoutez ici toutes les colonnes qui peuvent être assignées en masse
    protected $fillable = [
        'id_course','id_chauffeur','id_lieu_depart','id_lieu_arrivee','id_client','prix_reservation','date_prise_en_charge','duree_course','heure_arrivee','id_velo','terminee'
    ];
}
