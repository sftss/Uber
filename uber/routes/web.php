<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ChauffeurController;

Route::get('/', function() {
    return view('main');
});


Route::get('/courses', [CourseController::class, 'index']);
Route::get('/restaurants/search', [RestaurantController::class, 'filter'])->name('restaurants.filter');

Route::get('/restaurants/filter', [RestaurantController::class, 'filter'])->name('restaurants.filter');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('/map',[ChauffeurController::class, "index" ]);
Route::get('/test', function() {
    return view('main-test');
});


