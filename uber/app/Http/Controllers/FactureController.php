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
        $course = Course::with(['client', 'chauffeur', 'lieuDepart', 'lieuArrivee'])
            ->find($id_course);

        if (!$course) {
            return abort(404, 'Course non trouvée');
        }

        $company_name = "Uber";

        $items = [
            ['name' => 'Course', 'quantity' => 1, 'price' => $course->prix_reservation],
            ['name' => 'Pourboire', 'quantity' => 1, 'price' => $course->pourboire],
        ];
        $total = collect($items)->sum(fn($item) => $item['quantity'] * $item['price']);

        $date_prise_en_charge = Carbon::parse($course->date_prise_en_charge)
            ->locale('fr')
            ->isoFormat('D MMMM YYYY');

        // Correction pour la durée
        $duree = \Carbon\Carbon::parse($course->duree_course);
        $duree_course = $duree->format('H') . ' heure(s) et ' . $duree->format('i') . ' minute(s)';


        $totalHT = collect($items)->sum(fn($item) => $item['quantity'] * $item['price']); // Total hors taxes
        $tva = $totalHT * 0.20; 
        $totalTTC = $totalHT + $tva; 
        
        $data = [
            'company_name' => $company_name,
            'id_course' => $id_course,
            'items' => $items,
            'totalHT' => $totalHT,  
            'tva' => $tva,
            'totalTTC' => $totalTTC,
            'client' => $course->client,
            'chauffeur' => $course->chauffeur,
            'lieu_depart' => $course->lieuDepart,
            'lieu_arrivee' => $course->lieuArrivee,
            'pourboire' => $course->pourboire,
            'date_prise_en_charge' => $date_prise_en_charge,
            'duree_course' => $duree_course,
        ];

        $pdf = PDF::loadView('facture', $data);
        $file = 'Facture_id_course_' . $id_course . '.pdf';

        return $pdf->stream($file);
    }
}