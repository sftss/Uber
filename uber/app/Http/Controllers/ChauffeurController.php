<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chauffeur;
use App\Models\CategorieVehicule;

class ChauffeurController extends Controller
{
    public function index() {
        $chauffeurs = Chauffeur::with('adresse','vehicule.categorieVehicule')->get();
        $categories = CategorieVehicule::all();
        return view("map", ['chauffeurs' => $chauffeurs,'categories'=>$categories]);

    }

}
