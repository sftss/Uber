<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CB;
use App\Models\Possede;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CBController extends Controller
{
    public function __construct() {
        $this->middleware('auth'); 
    }

    public function store(Request $request, $id_client) {
        $validatedData = $request->validate([
            'num_cb' => 'required|numeric|digits:16',
            'nom_cb' => 'required|string|max:50',
            'date_fin_validite' => 'required|date_format:Y-m|after_or_equal:' . now()->format('Y-m'),
        ], [
            'num_cb.required' => 'Le numéro de la carte bancaire est requis.',
            'num_cb.numeric' => 'Le numéro de la carte bancaire doit contenir uniquement des chiffres.',
            'num_cb.digits' => 'Le numéro de la carte bancaire doit contenir exactement 16 chiffres.',
            'nom_cb.required' => 'Le nom du titulaire de la carte est requis.',
            'nom_cb.max' => 'Le nom du titulaire ne doit pas dépasser 50 caractères.',
            'date_fin_validite.required' => 'La date de validité est requise.',
            'date_fin_validite.after_or_equal' => 'La date de validité doit être postérieure ou égale au mois actuel.',
        ]);

        $dateFinValidite = $validatedData['date_fin_validite'] . '-01';

        try {
            $card = new CB();
            $card->num_cb = $validatedData['num_cb'];
            $card->nom_cb = $validatedData['nom_cb'];
            $card->date_fin_validite = $dateFinValidite; 
            $card->type_cb = $this->determineCardType($validatedData['num_cb']);
            $card->save();

            $possede=new Possede();
            $possede->id_client = $id_client;
            $possede->id_cb = $card->id_cb;
            $possede->save();

            return redirect()->route('profil', ['id_client' => $id_client])
                ->with('success', 'Carte bancaire ajoutée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout de la carte : ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    protected function determineCardType($cardNumber) {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);

        $patterns = [
            'Visa' => '/^4/',
            'MasterCard' => '/^(5[1-5]|22[2-9])/',
            'AMEX' => '/^3[47]/',
            'Diners' => '/^(36|38)/',
            'JCB' => '/^35[28-9]/'
        ];

        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $cardNumber)) {
                return $type;
            }
        }
        return 'Inconnu';
    }

    public function create() {
        return view('auth.add-card');
    }

    public function destroy($id_client, $id_cb) {
        if (Auth::check() && Auth::user()->id_client == $id_client) {

            DB::table('possede')
            ->where('id_cb',$id_cb)
            ->where('id_client',$id_client)
            ->delete();

            DB::table('cb')
                ->where('id_cb', $id_cb)
                ->delete();

            return redirect()->route('profil', ['id_client' => $id_client])
                             ->with('success', 'Carte bancaire supprimée avec succès.');
        }
        return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
    }

}
