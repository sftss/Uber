<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function create()
    {
        return view('produits.create');
    }

    // Stocke un nouveau produit
    public function store(Request $request)
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

        // Créer un nouveau produit
        Produit::create($validated);

        // Rediriger vers la liste des produits avec un message de succès
        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès');
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
