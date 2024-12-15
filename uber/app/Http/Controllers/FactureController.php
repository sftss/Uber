<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Course;
use App\Models\Adresse;
use App\Models\Chauffeur;
use App\Models\Client;
use Carbon\Carbon; 

class FactureController extends Controller
{
    public function genererFacture($id_course) {
        $course = Course::with(['client', 'chauffeur', 'lieuDepart', 'lieuArrivee'])->find($id_course);

        if (!$course) {
            return abort(404, 'Course non trouvée');
        }

        $company_name = "Uber";

        $items = [
            ['name' => 'Course', 'quantity' => 1, 'price' => $course->prix_reservation],
            ['name' => 'Pourboire', 'quantity' => 1, 'price' => $course->pourboire], 
        ];
        $total = collect($items)->sum(fn($item) => $item['quantity'] * $item['price']);

        $date_prise_en_charge = Carbon::parse($course->date_prise_en_charge)->locale('fr')->isoFormat('D MMMM YYYY');

        $duree_course_minutes = intval($course->duree_course);

        $hours = intdiv($duree_course_minutes, 60);
        $minutes = $duree_course_minutes % 60; 

        $duree_course = "{$hours} heure(s) et {$minutes} minute(s)";

        $data = [
                'company_name' => $company_name,
                'id_course' => $id_course,
                'id_chauffeur' => $course->id_chauffeur,
                'items' => $items,
                'total' => $total,
                'client' => $course->client, 
                'chauffeur' => $course->chauffeur,
                'lieu_depart' => $course->lieuDepart,
                'lieu_arrivee' => $course->lieuArrivee,
                'pourboire' => $course->pourboire,
                'date_prise_en_charge' => $date_prise_en_charge,
                'duree_course' => $duree_course,
            ];

        // dd($data);

        $pdf = PDF::loadView('facture', $data);
        $file = 'Facture_id_course_' . $id_course . '.pdf';

        return $pdf->stream($file);
        // return view("facture", $data);
    }
}