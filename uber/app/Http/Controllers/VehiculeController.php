<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicule;

class VehiculeController extends Controller
{
        public function VehiculeValider(){
            $vehicules = Vehicule::all();

            return view('service-lgt/voirvehicule', compact('vehicules'));
        }

        public function changerStatutVeh($vehicule_id, Request $request)
    {
        $vehicule = Vehicule::findOrFail($vehicule_id);
        
        if ($request->input('statut') == 'accepter') {
            $vehicule->valider = true; 
            $vehicule->save();
        } elseif ($request->input('statut') == 'refuser') {
            $vehicule->delete();
        }
        return redirect()->back()->with('success', $request->input('statut') == 'accepter' ? 'Véhicule accepté.' : 'Vehicule supprimé.');
    }

    public function ajoutAmenagement($vehicule_id, Request $request)
    {
        $vehicule = Vehicule::findOrFail($vehicule_id);
        
        $vehicule->valider = false; 
            $vehicule->save();
        
        return redirect()->back()->with('success', $request->input('statut') == 'accepter' ? 'Rendez-vous accepté.' : 'Chauffeur supprimé.');
    }
}
