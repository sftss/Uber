<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LieuVenteController extends Controller
{
    public function filter()
    {
        // Récupérer tous les lieux de vente avec leurs informations
        $lieux = DB::table('lieu_de_vente_pf')
            ->join('adresse', 'lieu_de_vente_pf.id_adresse', '=', 'adresse.id_adresse')
            ->select('id_lieu_de_vente_pf', 'nom_etablissement', 'horaires_ouverture', 'horaires_fermeture', 'description_etablissement', 'propose_livraison','photo_lieu', 'adresse.ville', 'adresse.rue', 'adresse.cp')
            ->get();

        // Retourner la vue avec les données
        return view('lieux.filter', compact('lieux'));
    }
}
