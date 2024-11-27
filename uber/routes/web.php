<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ClientController;


Route::get('/', function() {
    return view('restaurant-list');
});

Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/clients', [ClientController::class, 'index']);