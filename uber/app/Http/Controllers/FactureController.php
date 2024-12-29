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
public function genererFacture($id_course, Request $request)
{
    $langue = $request->input('langue', 'fr');
    App::setLocale($langue);
    
    $course = Course::with(['client', 'chauffeur', 'lieuDepart', 'lieuArrivee'])->findOrFail($id_course);

    if ($request->session()->has("facture_generee_{$id_course}")) {
        $factureExistante = Facture::where('id_course', $id_course)->first();
        return $this->genererPDF($course, $factureExistante, $langue);
    }

    $factureExistante = Facture::where('id_course', $id_course)->first();

    if ($factureExistante) {
        $request->session()->put("facture_generee_{$id_course}", true);
        return $this->genererPDF($course, $factureExistante, $langue);
    }

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
        'est_facture' => true,
    ]);

    $course->update(['est_facture' => true]);

    $request->session()->put("facture_generee_{$id_course}", true);
    
    return $this->genererPDF($course, $facture, $langue);
}

    private function genererPDF($course, $facture, $langue) {
        $items = [
            ['name' => __('facture.Ride'), 'price' => $course->prix_reservation, 'tva' => '20%'],
            ['name' => __('facture.Tip'), 'price' => $course->pourboire, 'tva' => '-'],
        ];
        
        $totalHT = collect($items)->where('tva', '20%')->sum(fn($item) => $item['price']);
        $tva = $totalHT * 0.20;
        $totalTTC = $totalHT + $tva + $course->pourboire;

        $data = [
            'id_course' => $course->id_course,
            'client' => $course->client,
            'chauffeur' => $course->chauffeur,
            'lieu_depart' => $course->lieuDepart,
            'lieu_arrivee' => $course->lieuArrivee,
            'items' => $items,
            'totalHT' => $totalHT,
            'tva' => $tva,
            'totalTTC' => $totalTTC,
            'pourboire' => $facture->pourboire,
            'date_prise_en_charge' => Carbon::parse($course->date_prise_en_charge)->locale($langue)->isoFormat('D MMMM YYYY'),
            'duree_course' => $course->duree_course,
        ];

        $pdf = Pdf::loadView('course/facture', $data);
        return $pdf->stream("facture_course_{$course->id_course}.pdf");
    }
}