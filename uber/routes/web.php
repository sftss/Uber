<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ChauffeurController;
use App\Http\Controllers\LieuVenteController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;



Route::get('/', function() {
    return view('main');
});


Route::get('/courses', [CourseController::class, 'index']);

Route::get('/restaurants/search', [RestaurantController::class, 'filter'])->name('restaurants.search');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('/lieux/search', [LieuVenteController::class, 'filter'])->name('lieux.search');
Route::get('/lieux/{id}', [LieuVenteController::class, 'show'])->name('lieux.show');


Route::get('/panierclient', [ClientController::class, 'panierclient']);


Route::get('/map',[ChauffeurController::class, "index" ]);
Route::get('/test', function() {
    return view('main-test');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// Confirmer l'email
Route::get('confirm-email/{code}', [RegistrationController::class, 'confirmEmail'])->name('confirm.email');


