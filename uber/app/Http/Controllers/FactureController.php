<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Barryvdh\DomPDF\Facade\Pdf;

class FactureController extends Controller
{
    public function genererFacture($id_course)
    {
        // Données de la facture (tu peux récupérer ces infos via ton modèle)
        $company_name = "Mon Entreprise";
        $items = [
            ['name' => 'Produit 1', 'quantity' => 2, 'price' => 50],
            ['name' => 'Produit 2', 'quantity' => 1, 'price' => 75],
        ];
        $total = collect($items)->sum(fn($item) => $item['quantity'] * $item['price']);

        // Préparation des données pour la vue
        $data = [
            'company_name' => $company_name,
            'items' => $items,
            'total' => $total
        ];

        // Chargement de la vue avec les données pour générer le PDF
        $pdf = Pdf::loadView('facture', $data);

        // Retourner le PDF pour téléchargement
        return $pdf->download('facture_course_' . $id_course . '.pdf');
    }
}
