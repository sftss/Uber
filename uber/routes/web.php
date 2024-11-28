<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CourseController;

Route::get('/', function() {
    return view('main');
});


Route::get('/courses', [CourseController::class, 'index']);
Route::get('/restaurants/search', [RestaurantController::class, 'search'])->name('restaurants.search');
Route::get('/restaurants/catPrestationSearch', [RestaurantController::class, 'filter'])->name('restaurants.filter');
Route::get('/restaurants/filter', [RestaurantController::class, 'filter'])->name('restaurants.filter');