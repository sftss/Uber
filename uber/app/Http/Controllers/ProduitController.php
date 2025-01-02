<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Vends;
use App\Models\EstVendu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RestaurantController;

class ProduitController extends Controller
{
    public function index() {
        $produits = Produit::all();
        return view('produits.index', compact('produits'));
    }

    public function show($id) {
        $produit = Produit::find($id);
        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé');
        }
        return view('produits.show', compact('produit'));
    }

    public function create($restaurant_id) {
        $categories = DB::table('categorie_produit')->get(); 
        return view('produit.create', [
            'restaurant_id' => $restaurant_id,
            'categories' => $categories 
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'id_restaurant' => 'required|integer|exists:restaurant,id_restaurant',
            'libelle_produit' => 'required|string|max:30', 
            'categorie_id' => 'required|integer|exists:categorie_produit,id_categorie_produit', 
            'prix_produit' => 'required|numeric|between:0,999.99',
            'photo_produit' => 'nullable|string|max:255',
        ], [
            'categorie_id.required' => 'Vous devez sélectionner une catégorie.',
            'categorie_id.exists' => 'La catégorie sélectionnée n’est pas valide.',
        ]);

        $Produit = new Produit();
        $Produit->id_categorie_produit = $request->categorie_id; 
        $Produit->nom_produit = $request->libelle_produit;
        $Produit->prix_produit = $request->prix_produit;
        $Produit->photo_produit = $request->photo_produit;
        $Produit->save();

        $vends = new Vends();
        $vends->id_restaurant = $request->id_restaurant;
        $vends->id_produit = $Produit->id_produit;
        $vends->save();

        return redirect()->action(
            [RestaurantController::class, 'show'],
            ['id' => $request->id_restaurant]
        )->with('successs', 'Produit ajouté avec succès');
    }

    public function edit($id) {
        $produit = Produit::find($id);
        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé');
        }
        return view('produits.edit', compact('produit'));
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'nom_produit' => 'required|string|max:30',
            'id_categorie_produit' => 'required|integer',
            'note_produit' => 'nullable|numeric|between:0,10',
            'nb_avis' => 'nullable|integer',
            'prix_produit' => 'required|numeric|between:0,999.99',
            'photo_produit' => 'nullable|string|max:255',
        ]);

        $produit = Produit::find($id);
        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé');
        }
        $produit->update($validated);
        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès');
    }

    public function destroy($id) {
        $produit = Produit::find($id);
        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé');
        }
        $produit->delete();
        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès');
    }

    public function createForLieu($lieu_id) {
        $categories = DB::table('categorie_produit')->get();
        return view('produit.createProduitLieuVente', [
            'lieu_id' => $lieu_id,
            'categories' => $categories,
        ]);
    }

    public function storeForLieu(Request $request, $lieu_id) {
        $validated = $request->validate([
            'libelle_produit' => 'required|string|max:30',
            'categorie_id' => 'required|integer|exists:categorie_produit,id_categorie_produit',
            'prix_produit' => 'required|numeric|between:0,999.99',
            'photo_produit' => 'nullable|string|max:255',
        ]);

        $produit = Produit::create([
            'id_categorie_produit' => $validated['categorie_id'],
            'nom_produit' => $validated['libelle_produit'],
            'prix_produit' => $validated['prix_produit'],
            'photo_produit' => $validated['photo_produit'],
        ]);

        EstVendu::create([
            'id_lieu_de_vente_pf' => $lieu_id,
            'id_produit' => $produit->id_produit,
        ]);

        return redirect()->route('lieux.show', $lieu_id)->with('success', 'Produit ajouté avec succès.');
    }
}
