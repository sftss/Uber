<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chauffeur;
use App\Models\CategorieVehicule;
use App\Models\Couleur;
use App\Models\Vehicule;
use App\Models\Course;

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



}
