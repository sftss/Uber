<?php

namespace App\Http\Controllers;

use App\Models\Produit;  
use App\Models\Plat;  
use App\Models\Menu;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;  // Utilisation de la session pour stocker le panier

class CartController extends Controller
{
    // Afficher le panier
    public function index()
{
    $cart = session()->get('cart', []);
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return view('cart.index', compact('cart', 'total'));
}

    // Ajouter un produit au panier
    public function add(Request $request, $type, $id)
{
    // Récupérer l'élément en fonction du type
    $item = null;
    if ($type === 'produit') {
        $item = Produit::find($id);
    } elseif ($type === 'menu') {
        $item = Menu::find($id);
    } elseif ($type === 'plat') {
        $item = Plat::find($id);
    }

    if (!$item) {
        return redirect()->route('home')->with('error', 'Élément non trouvé');
    }

    // Récupérer le panier de la session
    $cart = session()->get('cart', []);

    // Vérifier si l'élément est déjà dans le panier
    $key = $type . '_' . $id; // Utiliser une clé unique pour chaque type d'élément
    if (isset($cart[$key])) {
        // Augmenter la quantité
        $cart[$key]['quantity']++;
    } else {
        // Ajouter un nouvel élément au panier
        $cart[$key] = [
            'name' => $item->nom_produit ?? $item->libelle_menu ?? $item->libelle_plat,
            'price' => $item->prix_produit ?? $item->prix_menu ?? $item->prix_plat,
            'quantity' => 1,
            'image' => $item->photo_produit ?? $item->photo_menu ?? $item->photo_plat,
        ];
    }

    // Sauvegarder le panier dans la session
    session()->put('cart', $cart);

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
    public function update(Request $request, $uniqueId)
{
    $quantity = $request->input('quantity');
    $cart = session()->get('cart', []);

    if (isset($cart[$uniqueId])) {
        $cart[$uniqueId]['quantity'] = $quantity;
        session()->put('cart', $cart);
    }

    return redirect()->route('cart.index');
}
}
