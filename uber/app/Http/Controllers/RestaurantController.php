<?php

namespace App\Http\Controllers;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller {
    public function index() 
    {
        return view('restaurant-list', ['restaurants' => Restaurant::all()]);
    }

    public function search(Request $request) 
    {
        $query = $request->input('search');
        
        $restaurants = Restaurant::join('adresse', 'restaurant.id_adresse', '=', 'adresse.id_adresse')
            ->where('restaurant.nom_etablissement', 'LIKE', "%{$query}%")
            ->orWhere('adresse.ville', 'LIKE', "%{$query}%")
            ->select('restaurant.*', 'adresse.ville')
            ->get();
        
        return view('restaurants.search', compact('restaurants', 'query'));
    }

    public function filter(Request $request)
{
    $lieu = $request->input('lieu');
    $livre = $request->has('livre');     // Vérifie si la case livraison est cochée
    $emporter = $request->has('emporter'); // Vérifie si la case à emporter est cochée

    $restaurants = Restaurant::join('adresse', 'restaurant.id_adresse', '=', 'adresse.id_adresse')
        ->when($lieu, function ($query, $lieu) {
            return $query->where('adresse.ville', 'LIKE', "%{$lieu}%");
        })
        ->when($livre && !$emporter, function ($query) {
            // Filtre les restaurants qui proposent uniquement la livraison
            return $query->where('restaurant.propose_livraison', 1)
                         ->where('restaurant.propose_retrait', 0);
        })
        ->when($emporter && !$livre, function ($query) {
            // Filtre les restaurants qui proposent uniquement le retrait
            return $query->where('restaurant.propose_retrait', 1)
                         ->where('restaurant.propose_livraison', 0);
        })
        ->when($livre && $emporter, function ($query) {
            // Filtre les restaurants qui proposent à la fois livraison et retrait
            return $query->where('restaurant.propose_livraison', 1)
                         ->where('restaurant.propose_retrait', 1);
        })
        ->select('restaurant.*', 'adresse.ville')
        ->get();

    return view('restaurants.filter', compact('restaurants', 'lieu'));
}


}
