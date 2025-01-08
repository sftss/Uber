<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicule;

class VehiculeController extends Controller
{
        public function VehiculeValider(){
            $vehicules = Vehicule::all();

            // Retourner la vue avec les véhicules validés
            return view('service-lgt/voirvehicule', compact('vehicules'));
        }

        public function changerStatutVeh($vehicule_id, Request $request)
    {
        // Trouver le chauffeur par ID
        $vehicule = Vehicule::findOrFail($vehicule_id);
        
        // Si l'action est "accepter", mettre à jour validerrh à true
        if ($request->input('statut') == 'accepter') {
            $vehicule->valider = true; // Marquer comme accepté
            $vehicule->save();
        } elseif ($request->input('statut') == 'refuser') {
            // Si l'action est "refuser", supprimer le chauffeur de la base de données
            $vehicule->delete();
        }
        
        // Rediriger vers la page précédente avec un message de succès
        return redirect()->back()->with('success', $request->input('statut') == 'accepter' ? 'Véhicule accepté.' : 'Vehicule supprimé.');
    }

    public function ajoutAmenagement($vehicule_id, Request $request)
    {
        // Trouver le chauffeur par ID
        $vehicule = Vehicule::findOrFail($vehicule_id);
        
        $vehicule->valider = false; // Marquer comme accepté
            $vehicule->save();
        
        // Rediriger vers la page précédente avec un message de succès
        return redirect()->back()->with('success', $request->input('statut') == 'accepter' ? 'Rendez-vous accepté.' : 'Chauffeur supprimé.');
    }
}
