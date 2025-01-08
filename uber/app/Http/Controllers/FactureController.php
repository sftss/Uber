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

    public function genererFactures(Request $request)
{

    $id_courses = $request->input('id_courses');

    if (empty($id_courses)) {
        return redirect()->route('courses-chauffeur', ['id' => $id_courses[0]])->with('error', 'Aucune course sélectionnée.');
    }
    $langue = $request->input('langue', 'fr');
    App::setLocale($langue);

    // Récupérer toutes les courses correspondantes à la liste des id_courses
    $courses = Course::with(['client', 'chauffeur', 'lieuDepart', 'lieuArrivee'])
                     ->whereIn('id_course', $id_courses)
                     ->get();

    if ($courses->isEmpty()) {
        return response()->json(['message' => __('Aucune course trouvée.')], 404);
    }

    // Vérifier si la facture consolidée a déjà été générée pour ces courses
    $factureExistante = Facture::whereIn('id_course', $id_courses)->first();

    // Si une facture consolidée existe, renvoyer le PDF
    if ($factureExistante) {
        return $this->genererPDFConsolide($courses, $factureExistante, $langue);
    }

    // Initialiser les montants pour la facture consolidée
    $totalHT = 0;
    $tva = 0;
    $totalTTC = 0;
    $pourboireTotal = 0;
    $items = [];

    foreach ($courses as $course) {
        $items[] = ['name' => __('facture.Ride') . ' - ' . $course->id_course, 'price' => $course->prix_reservation, 'tva' => '20%'];
        $items[] = ['name' => __('facture.Tip') . ' - ' . $course->id_course, 'price' => $course->pourboire, 'tva' => '-'];

        $totalHT += $course->prix_reservation;
        $pourboireTotal += $course->pourboire;
    }

    $tva = $totalHT * 0.20;
    $totalTTC = $totalHT + $tva + $pourboireTotal;

    // Créer la facture consolidée
    $facture = Facture::create([
        'id_course' => implode(',', $id_courses), // Vous pouvez sauvegarder les IDs des courses dans un champ
        'montant_facture' => $totalTTC,
        'description_facture' => __('Facture consolidée pour les courses ID') . ' ' . implode(', ', $id_courses),
        'pourboire' => $pourboireTotal,
        'date_course' => now(),  // Date de création de la facture
        'taux_tva' => 0.20,
        'numero_mois' => Carbon::now()->month,
        'est_particulier' => true,
        'est_facture' => true,
    ]);

    // Mettre à jour le statut des courses comme ayant une facture générée
    foreach ($courses as $course) {
        $course->update(['est_facture' => true]);
    }

    // Générer et retourner le PDF consolidé
    return $this->genererPDFConsolide($courses, $facture, $langue);
}

private function genererPDFConsolide($courses, $facture, $langue)
{
    // Initialiser les données pour la facture consolidée
    $items = [];
    $totalHT = 0;
    $tva = 0;
    $totalTTC = 0;
    $pourboireTotal = 0;

    foreach ($courses as $course) {
        // Course - ajout dans les items
        $items[] = [
            'name' => __('facture.Ride') . ' - ' . $course->id_course, 
            'price' => $course->prix_reservation, 
            'tva' => '20%',
            'tip' => $course->pourboire // Ajouter pourboire dans le même item
        ];
    
        // Calcul du total HT (seulement pour les courses)
        $totalHT += $course->prix_reservation;
    
        // Calcul du total pourboire
        $pourboireTotal += $course->pourboire;
    }

    $tva = $totalHT * 0.20;
    $totalTTC = $totalHT + $tva + $pourboireTotal;

    // Préparer les données pour afficher dans la vue PDF
    $data = [
        'facture_id' => $facture->id,
        'client' => $courses->first()->client, // Utilisation du premier client (on suppose que tous les clients sont les mêmes)
        'chauffeur' => $courses->first()->chauffeur, // Utilisation du premier chauffeur
        'lieu_depart' => $courses->first()->lieuDepart,
        'lieu_arrivee' => $courses->first()->lieuArrivee,
        'items' => $items,
        'totalHT' => $totalHT,
        'tva' => $tva,
        'totalTTC' => $totalTTC,
        'pourboire' => $pourboireTotal,
        'date_prise_en_charge' => Carbon::now()->locale($langue)->isoFormat('D MMMM YYYY'),
    ];

    // Générer le PDF à partir de la vue
    $pdf = Pdf::loadView('service-facturation/facture_consolidee', $data);

    // Sauvegarder et retourner le PDF
    return $pdf->stream("facture_consolidee_{$facture->id}.pdf");
}

}