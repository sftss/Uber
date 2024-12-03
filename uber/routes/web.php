<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ChauffeurController;
use App\Http\Controllers\LieuVenteController;

Route::get('/', function() {
    return view('main');
});


Route::get('/courses', [CourseController::class, 'index']);

Route::get('/restaurants/search', [RestaurantController::class, 'filter'])->name('restaurants.search');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('/lieux/search', [LieuVenteController::class, 'filter'])->name('lieux.search');
Route::get('/lieux/{id}', [LieuVenteController::class, 'show'])->name('lieux.show');


Route::get('/map',[ChauffeurController::class, "index" ]);
Route::get('/test', function() {
    return view('main-test');
});


