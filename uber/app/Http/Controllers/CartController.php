<?php

namespace App\Http\Controllers;

use App\Models\Produit;  
use App\Models\Panier;  
use App\Models\Plat;  
use App\Models\Menu;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;  // Utilisation de la session pour stocker le panier
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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
        return redirect()->back()->with('error', 'Élément non trouvé');
    }

    // Ajouter l'élément au panier (session)
    $cart = session()->get('cart', []);
    $key = $type . '_' . $id;

    // Calculer le montant total pour cet élément
    $price = $item->prix_produit ?? $item->prix_menu ?? $item->prix_plat;
    $quantity = 1;
    $totalAmount = $price * $quantity;

    // Vérifier si l'élément est déjà dans le panier
    if (isset($cart[$key])) {
        // Augmenter la quantité et recalculer le montant total
        $cart[$key]['quantity']++;
        $totalAmount = $cart[$key]['quantity'] * $price;
    } else {
        // Ajouter un nouvel élément au panier
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

    // Vérifier si l'utilisateur est connecté
    if (Auth::check()) {
        $idClient = Auth::user()->id_client;
        $idPanier = $this->getPanierId($idClient);

        // Sauvegarder dans la base de données
        $this->saveToDatabase($type, $id, $idPanier, $cart[$key]);

        // Mettre à jour le montant total du panier dans la table panier
        $this->updateMontantPanier($idPanier);
    }

    // Rediriger vers la page précédente avec un message de succès
    return redirect()->back()->with('success', 'Votre article a correctement été ajouté au panier !');

}


















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

        if (Auth::check()) {
            // Si l'utilisateur est connecté, gérer la base de données
            $idClient = Auth::user()->id_client;
            $idPanier = $this->getPanierId($idClient);

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
    \DB::table('panier')
    ->where('id_panier', $idPanier)
    ->update([
        'montant' => $totalAmount
    ]);
}











private function saveToDatabase($type, $id, $idPanier, $cartItem)
{
    if ($type === 'produit') {
        $existing = \DB::table('contient')->where('id_panier', $idPanier)->where('id_produit', $id)->first();

        if ($existing) {
            // Si le produit existe déjà, mettre à jour la quantité
            \DB::table('contient')->where('id_panier', $idPanier)->where('id_produit', $id)->update([
                'quantite' => $existing->quantite + $cartItem['quantity']
            ]);
        } else {
            // Sinon, insérer un nouvel enregistrement
            \DB::table('contient')->insert([
                'id_panier' => $idPanier,
                'id_produit' => $id,
                'quantite' => $cartItem['quantity']
            ]);
        }
    } elseif ($type === 'menu') {
        $existing = \DB::table('contient_menu')->where('id_panier', $idPanier)->where('id_menu', $id)->first();

        if ($existing) {
            \DB::table('contient_menu')->where('id_panier', $idPanier)->where('id_menu', $id)->update([
                'quantite' => $existing->quantite + $cartItem['quantity']
            ]);
        } else {
            \DB::table('contient_menu')->insert([
                'id_panier' => $idPanier,
                'id_menu' => $id,
                'quantite' => $cartItem['quantity']
            ]);
        }
    } elseif ($type === 'plat') {
        $existing = \DB::table('contient_plat')->where('id_panier', $idPanier)->where('id_plat', $id)->first();

        if ($existing) {
            \DB::table('contient_plat')->where('id_panier', $idPanier)->where('id_plat', $id)->update([
                'quantite' => $existing->quantite + $cartItem['quantity']
            ]);
        } else {
            \DB::table('contient_plat')->insert([
                'id_panier' => $idPanier,
                'id_plat' => $id,
                'quantite' => $cartItem['quantity']
            ]);
        }
    }
}




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

        if (Auth::check()) {
            // Si l'utilisateur est connecté, mettre à jour la base de données
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




public function passercomande(){
    if (Auth::check()) {
        $idClient = Auth::user()->id_client;
        $idPanier = $this->getPanierId($idClient);
        
        $client = DB::table('client as c')
            ->leftJoin('possede as p', 'p.id_client', '=', 'c.id_client')
            ->leftJoin('cb as cb', 'cb.id_cb', '=', 'p.id_cb')
            ->select('c.prenom_cp', 'c.nom_cp', 'c.mail_client', 'c.tel_client', 'cb.num_cb', 'cb.nom_cb', 'cb.date_fin_validite', 'cb.type_cb', 'cb.id_cb')
            ->where('c.id_client', $idClient)
            ->get();
    
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

        // Récupérer toutes les adresses du client
        $adresses = DB::table('se_fait_livrer_a as sf')
            ->join('adresse as a', 'sf.id_adresse', '=', 'a.id_adresse')
            ->where('sf.id_client', $idClient)
            ->select('a.ville', 'a.cp', 'a.rue')
            ->get(); // Utilisation de `get()` pour récupérer toutes les adresses

        return view('cart.confirm', compact('client', 'menus', 'produits', 'plats', 'adresses', 'total'));
    }

    return redirect()->route('login');
}


}
