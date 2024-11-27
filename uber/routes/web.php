<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CourseController;

Route::get('/', function() {
    return view('restaurant-list');
});

Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/clients', [ClientController::class, 'index']);
Route::get('/courses', [CourseController::class, 'index']);