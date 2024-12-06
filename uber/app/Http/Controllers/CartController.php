<?php

namespace App\Http\Controllers;

use App\Models\Produit;  
use App\Models\Panier;  
use App\Models\Plat;  
use App\Models\Menu;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;  // Utilisation de la session pour stocker le panier
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Afficher le panier
    public function index()
{
    $cart = session()->get('cart', []);
    $total = 0;



    



    $menus = [];
    $plats = [];
    $produits = [];

    // Séparer les éléments du panier par catégorie
    foreach ($cart as $key => $item) {
        $total += $item['price'] * $item['quantity'];

        // Déterminer le type de l'élément à partir de la clé
        if (str_contains($key, 'menu')) {
            $menus[$key] = $item;
        } elseif (str_contains($key, 'plat')) {
            $plats[$key] = $item;
        } elseif (str_contains($key, 'produit')) {
            $produits[$key] = $item;
        }
    }

    return view('cart.index', compact('menus', 'plats', 'produits', 'total'));
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

    // Récupérer ou initialiser l'id_panier
    $idClient = Auth::user()->id_client;

    if ($idClient) {
        $idPanier = $this->getPanierId($idClient);

        // Ajouter l'élément au panier en utilisant la session
        $cart = session()->get('cart', []);
        $key = $type . '_' . $id;

        // Calculer le montant total pour cet élément
        $price = $item->prix_produit ?? $item->prix_menu ?? $item->prix_plat;
        $quantity = 1;
        $totalAmount = $price * $quantity;

        // Vérifier si l'élément est déjà dans le panier dans la session
        if (isset($cart[$key])) {
            // Augmenter la quantité et recalculer le montant total
            $cart[$key]['quantity']++;
            $totalAmount = $cart[$key]['quantity'] * $price;
        } else {
            // Ajouter un nouvel élément au panier dans la session
            $cart[$key] = [
                'name' => $item->nom_produit ?? $item->libelle_menu ?? $item->libelle_plat,
                'price' => $price,
                'quantity' => $quantity,
                'total' => $totalAmount,
                'image' => $item->photo_produit ?? $item->photo_menu ?? $item->photo_plat,
            ];
        }

        // Sauvegarder le panier dans la session
        session()->put('cart', $cart);

        // Sauvegarder dans la base de données (tables CONTIENT, CONTIENT_MENU, CONTIENT_PLAT)
        if ($type === 'produit') {
            // Vérifier si le produit est déjà dans le panier
            $existing = \DB::table('contient')->where('id_panier', $idPanier)->where('id_produit', $id)->first();

            if ($existing) {
                // Si le produit existe déjà, mettre à jour la quantité et le montant
                \DB::table('contient')->where('id_panier', $idPanier)->where('id_produit', $id)->update([
                    'quantite' => $existing->quantite + $cart[$key]['quantity']
                ]);
            } else {
                // Sinon, insérer un nouvel enregistrement
                \DB::table('contient')->insert([
                    'id_panier' => $idPanier,
                    'id_produit' => $id,
                    'quantite' => $cart[$key]['quantity']
                ]);
            }
        } elseif ($type === 'menu') {
            // Vérifier si le menu est déjà dans le panier
            $existing = \DB::table('contient_menu')->where('id_panier', $idPanier)->where('id_menu', $id)->first();

            if ($existing) {
                // Si le menu existe déjà, mettre à jour la quantité et le montant
                \DB::table('contient_menu')->where('id_panier', $idPanier)->where('id_menu', $id)->update([
                    'quantite' => $existing->quantite + $cart[$key]['quantity']
                ]);
            } else {
                // Sinon, insérer un nouvel enregistrement
                \DB::table('contient_menu')->insert([
                    'id_panier' => $idPanier,
                    'id_menu' => $id,
                    'quantite' => $cart[$key]['quantity']
                ]);
            }
        } elseif ($type === 'plat') {
            // Vérifier si le plat est déjà dans le panier
            $existing = \DB::table('contient_plat')->where('id_panier', $idPanier)->where('id_plat', $id)->first();

            if ($existing) {
                // Si le plat existe déjà, mettre à jour la quantité et le montant
                \DB::table('contient_plat')->where('id_panier', $idPanier)->where('id_plat', $id)->update([
                    'quantite' => $existing->quantite + $cart[$key]['quantity']
                ]);
            } else {
                // Sinon, insérer un nouvel enregistrement
                \DB::table('contient_plat')->insert([
                    'id_panier' => $idPanier,
                    'id_plat' => $id,
                    'quantite' => $cart[$key]['quantity']
                ]);
            }
        }

        // Mettre à jour le montant total du panier dans la table panier
        $this->updateMontantPanier($idPanier);

        return redirect()->route('cart.index');
    }

    return redirect()->route('home')->with('error', 'Utilisateur non authentifié');
}

















// Supprimer un produit du panier
public function remove($id)
{
    // Récupérer le panier de la session
    $cart = session()->get('cart', []);

    // Vérifier si l'élément existe dans le panier
    if (isset($cart[$id])) {
        // Supprimer l'élément du panier
        unset($cart[$id]);

        // Sauvegarder à nouveau le panier dans la session
        session()->put('cart', $cart);

        // Récupérer l'ID du client et mettre à jour la base de données
        $idClient = Auth::user()->id_client;
        $idPanier = $this->getPanierId($idClient);

        // Extraire le type (produit, plat, menu) et l'ID de l'élément
        $item = explode('_', $id);
        $type = $item[0];
        $idElement = $item[1];

        // Supprimer l'élément de la base de données
        if ($type === 'produit') {
            \DB::table('contient')->where('id_panier', $idPanier)->where('id_produit', $idElement)->delete();
        } elseif ($type === 'menu') {
            \DB::table('contient_menu')->where('id_panier', $idPanier)->where('id_menu', $idElement)->delete();
        } elseif ($type === 'plat') {
            \DB::table('contient_plat')->where('id_panier', $idPanier)->where('id_plat', $idElement)->delete();
        }

        // Mettre à jour le montant total du panier dans la table panier
        $this->updateMontantPanier($idPanier);
    }

    return redirect()->route('cart.index');
}


public function updateMontantPanier($idPanier)
{
    $cart = session()->get('cart', []);
    $totalAmount = 0;

    // Calculer le montant total du panier
    foreach ($cart as $item) {
        $totalAmount += $item['total'];  // Utiliser le montant total de chaque élément
    }

    // Mettre à jour le montant dans la table panier
    \DB::table('panier')->where('id_panier', $idPanier)->update([
        'montant' => $totalAmount
    ]);
}
















// Mettre à jour la quantité d'un produit dans le panier
public function update(Request $request, $uniqueId)
{
    // Récupérer la nouvelle quantité
    $quantity = $request->input('quantity');

    // Récupérer le panier de la session
    $cart = session()->get('cart', []);

    // Vérifier si l'élément existe dans le panier
    if (isset($cart[$uniqueId])) {
        // Calculer le montant total pour cet élément
        $item = explode('_', $uniqueId); // On récupère le type (produit, plat, menu) et l'ID
        $type = $item[0];
        $id = $item[1];

        $price = 0;
        if ($type === 'produit') {
            $price = Produit::find($id)->prix_produit;
        } elseif ($type === 'menu') {
            $price = Menu::find($id)->prix_menu;
        } elseif ($type === 'plat') {
            $price = Plat::find($id)->prix_plat;
        }

        // Mettre à jour la quantité dans la session
        $cart[$uniqueId]['quantity'] = $quantity;

        // Calculer le montant total
        $cart[$uniqueId]['total'] = $price * $quantity;

        // Si la quantité est zéro, on supprime l'élément du panier
        if ($quantity == 0) {
            unset($cart[$uniqueId]);
        }

        // Sauvegarder le panier dans la session
        session()->put('cart', $cart);

        // Récupérer l'ID du panier et mettre à jour la base de données
        $idClient = Auth::user()->id_client;
        $idPanier = $this->getPanierId($idClient);

        if ($type === 'produit') {
            \DB::table('contient')->where('id_panier', $idPanier)->where('id_produit', $id)->update([
                'quantite' => $quantity
            ]);
        } elseif ($type === 'menu') {
            \DB::table('contient_menu')->where('id_panier', $idPanier)->where('id_menu', $id)->update([
                'quantite' => $quantity
            ]);
        } elseif ($type === 'plat') {
            \DB::table('contient_plat')->where('id_panier', $idPanier)->where('id_plat', $id)->update([
                'quantite' => $quantity
            ]);
        }

        // Mettre à jour le montant total du panier dans la table panier
        $this->updateMontantPanier($idPanier);
    }

    // Rediriger vers le panier avec la mise à jour
    return redirect()->route('cart.index');
}





public function getPanierId($idClient)
{
    // Récupérer le panier associé au client qui a est_commande à false
    $panier = \App\Models\Panier::where('id_client', $idClient)
                ->where('est_commande', false)
                ->first();

    if ($panier) {
        return $panier->id_panier;
    }

    return null; // Si aucun panier n'est trouvé
}

}