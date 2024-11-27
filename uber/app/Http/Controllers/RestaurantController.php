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
        $categorie = $request->input('categorie');

        $restaurants = Restaurant::join('adresse', 'restaurant.id_adresse', '=', 'adresse.id_adresse')
            ->when($lieu, function ($query, $lieu) {
                return $query->where('adresse.ville', 'LIKE', "%{$lieu}%");
            })
            ->when($categorie, function ($query, $categorie) {
                return $query->where('restaurant.categorie', 'LIKE', "%{$categorie}%");
            })
            ->select('restaurant.*', 'adresse.ville') 
            ->get();

        return view('.restaurantsfilter', compact('restaurants', 'lieu', 'categorie'));
    }
}
