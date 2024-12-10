<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chauffeur;
use App\Models\CategorieVehicule;
use App\Models\Couleur;
use App\Models\Vehicule;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class ChauffeurController extends Controller
{
    public function index($id = null) 
{
    $chauffeurs = Chauffeur::with('adresse','vehicule.categorieVehicule','vehicule.couleur','adresse.departement')->get();
    $categories = CategorieVehicule::all();
    
    $coursePourModification = null;
    if ($id) {
        $coursePourModification = Course::with(['lieuDepart', 'lieuArrivee'])->findOrFail($id);
    }

    return view("map", [
        'chauffeurs' => $chauffeurs,
        'categories' => $categories,
        'coursePourModification' => $coursePourModification
    ]);
}

public function AfficherPropositions($id)
{
    $courses = DB::table('course')
        ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
        ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
        ->join('chauffeur as ch','course.id_chauffeur','=','ch.id_chauffeur')
        ->where('ch.id_chauffeur', '=', $id)
        ->where('course.acceptee','!=','true')
        ->orwhere('course.acceptee','is','null')
        ->select(
            'course.id_course',
            'course.id_chauffeur',
            'course.prix_reservation',
            'course.date_prise_en_charge',
            'course.duree_course',
            'course.heure_arrivee',
            'course.terminee',
            'course.id_velo',
            'depart.ville as ville_depart',
            'ch.prenom_chauffeur',
            'ch.nom_chauffeur',
            'arrivee.ville as ville_arrivee',
            'course.acceptee'
        )
        ->orderBy('course.id_course', 'desc')
        ->paginate(5);    

    return view('chauffeur-propositions', ['courses' => $courses]);
}

public function AfficherCoursesPassees($id)
{
    $courses = DB::table('course')
        ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
        ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
        ->join('chauffeur as ch','course.id_chauffeur','=','ch.id_chauffeur')
        ->where('ch.id_chauffeur', '=', $id)
        ->where('course.terminee','=','true')
        ->select(
            'course.id_course',
            'course.id_chauffeur',
            'course.prix_reservation',
            'course.date_prise_en_charge',
            'course.duree_course',
            'course.heure_arrivee',
            'course.terminee',
            'course.id_velo',
            'depart.ville as ville_depart',
            'ch.prenom_chauffeur',
            'ch.nom_chauffeur',
            'arrivee.ville as ville_arrivee',
            'course.acceptee'
        )
        ->orderBy('course.id_course', 'desc')
        ->paginate(5);    

    return view('chauffeur-propositions', ['courses' => $courses]);
}



}
