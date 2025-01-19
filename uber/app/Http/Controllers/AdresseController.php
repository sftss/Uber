<?php

namespace App\Http\Controllers;

use App\Models\Adresse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Chauffeur;
use Illuminate\Support\Collection;

use function Laravel\Prompts\form;

class AdresseController extends Controller
{
    public function show($id)
    {
        $address = Adresse::find($id);

        if ($address) {
            return response()->json([
                'rue' => $address->rue,
                'ville' => $address->ville,
                'cp' => $address->cp
            ]);
        } else {
            return response()->json(['error' => 'Address not found'], 404);
        }
    }

    public function traitement(Request $request)
    {
        $departement = strtoupper($request->input('departement')); // Convertir en majuscules
    
        // Transformation pour les départements corses
        if ($departement === '2A') {
            $resultat = 20;
        } elseif ($departement === '2B') {
            $resultat = 21;
        } elseif (is_numeric($departement) && $departement > 20) {
            $resultat = $departement + 1;
        } else {
            $resultat = $departement;
        }
    
        // Récupération des courses
        $courses = DB::table('course')
            ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
            ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
            ->where('course.terminee', '!=', 'true')
            ->where('depart.id_departement', '=', (int)$resultat)
            ->whereNull('course.id_chauffeur')
            ->whereNull('course.id_velo')
            ->select(
                'course.id_course',
                'course.prix_reservation',
                'course.date_prise_en_charge',
                'course.duree_course',
                'course.heure_arrivee',
                'course.terminee',
                'course.id_velo',
                'depart.ville as ville_depart',
                'depart.id_departement as code_dep',
                'arrivee.ville as ville_arrivee',
                'course.acceptee',
                'course.id_lieu_depart',
                'course.id_lieu_arrivee',
                'course.id_client'
            )
            ->orderBy('course.id_course', 'desc')
            ->paginate(5);
    
        // Récupération des chauffeurs avec leurs adresses
        $chauffeurs = Chauffeur::with('adresse')->get();


    
        // Filtrage des chauffeurs suceptibles d'accepter la course
        $filteredChauffeurs = new Collection();

        //version avec la liste de chauffeurs par département (plus rapide mais moins précis)
        foreach ($chauffeurs as $chauffeur) {
            $idDepartement = $chauffeur->adresse->id_departement;
            if($idDepartement == (int)$resultat)
            {
                $filteredChauffeurs->push($chauffeur);
            }
        }


        
        //version avec calcul d'itinéraire (moins rapide mais plus précis)
/*
        foreach ($courses as $course) {
            $courseCoordinates = $this->getCoordinatesFromAddress($course->ville_depart);
    
            if ($courseCoordinates) {
                foreach ($chauffeurs as $chauffeur) {
                    $chauffeurAddress = $chauffeur->adresse->rue . ', ' . $chauffeur->adresse->ville . ', ' . $chauffeur->adresse->cp;
                    $chauffeurCoordinates = $this->getCoordinatesFromAddress($chauffeurAddress);
    
                    if ($chauffeurCoordinates) {
                        $distance = $this->calculateDistance(
                            $courseCoordinates['lat'],
                            $courseCoordinates['lon'],
                            $chauffeurCoordinates['lat'],
                            $chauffeurCoordinates['lon']
                        );
    
                        if ($distance <= 42) { // Chauffeurs à moins de 30 km
                            $filteredChauffeurs->push($chauffeur);
                        }
                    }
                }
            }
        }*/
    
        return view('servicecourse/voircourse', compact('courses', 'chauffeurs', 'filteredChauffeurs'));
    }
}
