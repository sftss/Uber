<?php

namespace App\Http\Controllers;

use App\Models\Adresse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $departement = strtoupper($request->input('departement')); // Récupérer la valeur et la convertir en majuscule

        // Appliquer les règles de transformation
        if ($departement === '2A') {
            $resultat = 20;
        } elseif ($departement === '2B') {
            $resultat = 21;
        } elseif (is_numeric($departement) && $departement > 20) {
            $resultat = $departement + 1;
        } else {
            $resultat = $departement;
        }

        $courses = DB::table('course')
        ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
        ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
        ->where('course.terminee','!=','true')
        ->where('depart.id_departement','=',(int)$resultat)
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
        )
        ->orderBy('course.id_course', 'desc')
        ->paginate(5);    

    return view('servicecourse/voircourse', compact('courses'));
    }
}
