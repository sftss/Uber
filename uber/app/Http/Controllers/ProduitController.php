<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Vends;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RestaurantController;

class ProduitController extends Controller
{
    // Affiche tous les produits
    public function index()
    {
        // Récupérer tous les produits
        $produits = Produit::all();

        // Retourner la vue avec la liste des produits
        return view('produits.index', compact('produits'));
    }

    // Affiche un produit spécifique
    public function show($id)
    {
        // Récupérer le produit par son ID
        $produit = Produit::find($id);

        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé');
        }

        // Retourner la vue avec le produit
        return view('produits.show', compact('produit'));
    }

    // Affiche le formulaire pour créer un nouveau produit
    public function create($restaurant_id)
    {
        $categories = DB::table('categorie_produit')->get(); 

        return view('produit.create', [
            'restaurant_id' => $restaurant_id,
            'categories' => $categories 
        ]);
    }

    // Stocke un nouveau produit
    public function store(Request $request)
    {
        // Valider les données de la requête
        $validated = $request->validate([
            'id_restaurant' => 'required|integer|exists:restaurant,id_restaurant',
            'libelle_produit' => 'required|string|max:30', // Mise à jour du nom du champ
            'categorie_id' => 'required|integer|exists:categorie_produit,id_categorie_produit', // Mise à jour du nom du champ
            'prix_produit' => 'required|numeric|between:0,999.99',
            'photo_produit' => 'nullable|string|max:255',
        ]);

        // Création du produit
        $Produit = new Produit();
        $Produit->id_categorie_produit = $request->categorie_id; // Utilisation de 'categorie_id'
        $Produit->nom_produit = $request->libelle_produit; // Utilisation de 'libelle_produit'
        $Produit->prix_produit = $request->prix_produit;
        $Produit->photo_produit = $request->photo_produit;
        $Produit->save();

        // Associer le produit au restaurant
        $vends = new Vends();
        $vends->id_restaurant = $request->id_restaurant;
        $vends->id_produit = $Produit->id_produit;
        $vends->save();

        // Redirection après l'ajout
        return redirect()->action(
            [RestaurantController::class, 'show'],
            ['id' => $request->id_restaurant]
        )->with('successs', 'Produit ajouté avec succès');



    }


    // Affiche le formulaire pour éditer un produit existant
    public function edit($id)
    {
        // Récupérer le produit à éditer
        $produit = Produit::find($id);

        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé');
        }

        // Retourner la vue d'édition avec le produit
        return view('produits.edit', compact('produit'));
    }

    // Met à jour les informations d'un produit
    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $validated = $request->validate([
            'nom_produit' => 'required|string|max:30',
            'id_categorie_produit' => 'required|integer',
            'note_produit' => 'nullable|numeric|between:0,10',
            'nb_avis' => 'nullable|integer',
            'prix_produit' => 'required|numeric|between:0,999.99',
            'photo_produit' => 'nullable|string|max:255',
        ]);

        // Trouver le produit à mettre à jour
        $produit = Produit::find($id);

        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé');
        }

        // Mettre à jour le produit
        $produit->update($validated);

        // Rediriger vers la liste des produits avec un message de succès
        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès');
    }

    // Supprime un produit
    public function destroy($id)
    {
        // Trouver le produit à supprimer
        $produit = Produit::find($id);

        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé');
        }

        // Supprimer le produit
        $produit->delete();

        // Rediriger vers la liste des produits avec un message de succès
        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès');
    }
}
