<?php

namespace App\Http\Controllers;

use App\Models\Produit;  // Assurez-vous d'utiliser le bon modèle pour les produits
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;  // Utilisation de la session pour stocker le panier

class CartController extends Controller
{
    // Afficher le panier
    public function index()
    {
        // Récupérer les articles du panier stockés dans la session
        $cart = session()->get('cart', []);

        // Calculer le montant total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Retourner la vue avec les données du panier
        return view('cart.index', compact('cart', 'total'));
    }

    // Ajouter un produit au panier
    public function add(Request $request, $id)
    {
        // Récupérer le produit par ID
        $product = Produit::find($id);  // Assurez-vous d'utiliser le modèle Produit approprié
        if (!$product) {
            return redirect()->route('home')->with('error', 'Produit non trouvé');
        }

        // Récupérer le panier de la session
        $cart = session()->get('cart', []);

        // Vérifier si le produit est déjà dans le panier
        if (isset($cart[$id])) {
            // Augmenter la quantité
            $cart[$id]['quantity']++;
        } else {
            // Ajouter un nouveau produit au panier
            $cart[$id] = [
                'name' => $product->nom_produit,  // Assurez-vous d'utiliser le bon attribut
                'price' => $product->prix_produit,  // Assurez-vous d'utiliser le bon attribut
                'quantity' => 1,
            ];
        }

        // Sauvegarder le panier dans la session
        session()->put('cart', $cart);

        // Retourner une réponse ou rediriger
        return redirect()->route('cart.index');
    }

    // Supprimer un produit du panier
    public function remove($id)
    {
        // Récupérer le panier de la session
        $cart = session()->get('cart', []);

        // Vérifier si le produit existe dans le panier
        if (isset($cart[$id])) {
            // Supprimer l'élément du panier
            unset($cart[$id]);

            // Sauvegarder à nouveau le panier dans la session
            session()->put('cart', $cart);
        }

        // Rediriger vers le panier
        return redirect()->route('cart.index');
    }

    // Mettre à jour la quantité d'un produit dans le panier
    public function update(Request $request, $id)
    {
        // Récupérer la quantité souhaitée à partir de la requête
        $quantity = $request->input('quantity');

        // Récupérer le panier de la session
        $cart = session()->get('cart', []);

        // Vérifier si le produit existe dans le panier
        if (isset($cart[$id])) {
            // Mettre à jour la quantité du produit
            $cart[$id]['quantity'] = $quantity;
        }

        // Sauvegarder à nouveau le panier dans la session
        session()->put('cart', $cart);

        // Rediriger vers le panier
        return redirect()->route('cart.index');
    }
}
