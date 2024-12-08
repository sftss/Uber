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
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdresseController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers;
use App\Http\Controllers\MailController;

Route::get('/', function() {
    return view('main');
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');




Route::get('/restaurants/search', [RestaurantController::class, 'filter'])->name('restaurants.search');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('/lieux/search', [LieuVenteController::class, 'filter'])->name('lieux.search');
Route::get('/lieux/{id}', [LieuVenteController::class, 'show'])->name('lieux.show');


Route::get('/panierclient', [ClientController::class, 'panierclient']);


Route::get('/map',[ChauffeurController::class, "index" ]);
Route::get('/map/{id?}', [ChauffeurController::class, 'index'])->name('map');


Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('add/{id}', [CartController::class, 'add'])->name('add');
    Route::delete('remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::patch('update/{id}', [CartController::class, 'update'])->name('update');
});




Route::post('/cart/add/{type}/{id}', [CartController::class, 'add'])->name('cart.add');

Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/panier/supprimer/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('update/{id}', [CartController::class, 'update'])->name('update');

Route::post('add/{type}/{id}', [CartController::class, 'add'])->name('cart.add');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/politique', function () {
    return view('politique');
})->name('politique');


Route::get('confirm-email/{code}', [RegistrationController::class, 'confirmEmail'])->name('confirm.email');


Route::get('/adresse/{id}', [AdresseController::class, 'show']);

Route::get('/verify', [RegisterController::class, 'showVerificationForm'])->name('verify.form');
Route::post('/verify', [RegisterController::class, 'verifyCode'])->name('verify.code');

Route::get('/verification', [VerificationController::class, 'showVerificationPage'])->name('verification.page');
Route::post('/verification', [VerificationController::class, 'verifyCode'])->name('verification.submit');
