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
}
