<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chauffeur;
use App\Models\CategorieVehicule;
use App\Models\Couleur;
use App\Models\Vehicule;

class ChauffeurController extends Controller
{
    public function index() {
        $chauffeurs = Chauffeur::with('adresse','vehicule.categorieVehicule','vehicule.couleur','adresse.departement')->get();
        $categories = CategorieVehicule::all();
        return view("map", ['chauffeurs' => $chauffeurs,'categories'=>$categories]);

    }

}
