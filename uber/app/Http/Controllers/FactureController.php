<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureController extends Controller
{
    public function genererFacture($id_course, Request $request) {
        $langue = $request->input('langue', 'fr');
        App::setLocale($langue);

        $course = Course::with(['client', 'chauffeur', 'lieuDepart', 'lieuArrivee'])->find($id_course);

        $date_prise_en_charge = Carbon::parse($course->date_prise_en_charge)
            ->locale($langue)
            ->isoFormat('D MMMM YYYY');

        $duree = Carbon::parse($course->duree_course);
        $duree_course = match ($langue) {
            'fr' => $duree->format('H') . ' heure(s) et ' . $duree->format('i') . ' minute(s)',
            'en' => $duree->format('H') . ' hour(s) and ' . $duree->format('i') . ' minute(s)',
            'de' => $duree->format('H') . ' Stunde(n) und ' . $duree->format('i') . ' Minute(n)',
            'es' => $duree->format('H') . ' hora(s) y ' . $duree->format('i') . ' minuto(s)',
            'it' => $duree->format('H') . ' ora(e) e ' . $duree->format('i') . ' minuto(i)',
            default => $duree->format('H') . ' hour(s) and ' . $duree->format('i') . ' minute(s)',
        };

        $items = [
            ['name' => __('facture.Ride'), 'price' => $course->prix_reservation, 'tva' => '20%'],
            ['name' => __('facture.Tip'), 'price' => $course->pourboire, 'tva' => '-'],
        ];

        $totalHT = collect($items)->where('tva', '20%')->sum(fn($item) => $item['price']);
        $tva = $totalHT * 0.20;
        $totalTTC = $totalHT + $tva + $course->pourboire;

        $facture = Facture::create([
            'id_course' => $id_course,
            'montant_facture' => $totalTTC,
            'description_facture' => __('Facture pour la course ID') . ' ' . $id_course,
            'pourboire' => $course->pourboire,
            'date_course' => $course->date_prise_en_charge,
            'taux_tva' => 0.20,
            'numero_mois' => Carbon::parse($course->date_prise_en_charge)->month,
            'est_particulier' => true,
        ]);

        $data = [
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

        $pdf = Pdf::loadView('facture/facture', $data);
        $file = 'uber_id_' . $id_course . '.pdf';

        return $pdf->stream($file);
    }
}
