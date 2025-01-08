<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ServiceFacturationController extends Controller
{
    public function afficher() {
        

        return view('service-facturation/visualisation');
    }

    public function afficherCoursesChauffeur($id)
    {
        $courses = DB::table('course')
            ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
            ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
            ->join('chauffeur as ch','course.id_chauffeur','=','ch.id_chauffeur')
            ->where('course.id_chauffeur', '=', $id)
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
            ->paginate(50);    

        return view('service-facturation/courses-chauffeur', ['courses' => $courses,
        'id' => $id,]);
    }

    public function afficherCoursesChauffeurPeriode(Request $request, $id)
{
    // Récupérer les dates de la requête, avec un fallback si elles sont vides
    $debut = $request->get('start_date');
    $fin = $request->get('end_date');

    // Si les dates sont présentes, on les utilise. Sinon, on les définit sur les dates par défaut
    if ($debut && $fin) {
        // Convertir les dates en format 'Y-m-d' pour les comparaisons SQL
        $debut = \Carbon\Carbon::createFromFormat('Y-m-d', $debut)->startOfDay();
        $fin = \Carbon\Carbon::createFromFormat('Y-m-d', $fin)->endOfDay();
    }

    $courses = DB::table('course')
        ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
        ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
        ->join('chauffeur as ch', 'course.id_chauffeur', '=', 'ch.id_chauffeur')
        ->where('ch.id_chauffeur', '=', $id)
        ->where('course.terminee', '=', 'true')
        // Appliquer le filtre de la période
        ->when($debut && $fin, function ($query) use ($debut, $fin) {
            return $query->whereBetween('course.date_prise_en_charge', [$debut, $fin]);
        })
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
        ->paginate(50);

        return view('service-facturation/courses-chauffeur', [
            'courses' => $courses,
            'id' => $id,
        ]);
}
}
