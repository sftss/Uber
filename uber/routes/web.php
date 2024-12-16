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
use App\Http\Controllers\MailController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers;
use App\Http\Controllers\CBController;
use App\Http\Controllers\FactureController;

Route::get('/', function () {
    return view('main');
})->name('home');


Route::get('/test-mail', [MailController::class, 'sendMail']);

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

Route::put('/courses/{id}', [ChauffeurController::class, 'index'])->name('courses.update');

Route::put('/courses/refuser/{course}', [CourseController::class, 'refuser'])->name('courses.refuser');
Route::put('/courses/accepter/{course}', [CourseController::class, 'accepter'])->name('courses.accepter');

Route::put('/courses/terminer-chauffeur/{course}', [ChauffeurController::class, 'terminer'])->name('chauffeur.terminer');
Route::put('/courses/terminer-client/{course}', [ClientController::class, 'terminer'])->name('client.terminer');

Route::put('/courses/{id}/terminer', [CourseController::class, 'terminer'])->name('client.terminer');
Route::post('/courses/{courseId}/review', [CourseController::class, 'submitReview'])->name('courses.submitReview');

Route::post('/courses/{id_course}/review', [CourseController::class, 'submitReview'])->name('courses.submitReview');

Route::get('/facture', [FactureController::class, 'genererFacture']);
Route::post('/courses/{id_course}/Facture', [FactureController::class, 'genererFacture'])
    ->name('courses.Facture');

Route::get('/restaurants/filter', [RestaurantController::class, 'filter'])->name('restaurants.filter');

Route::get('/restaurants/search', [RestaurantController::class, 'filter'])->name('restaurants.search');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('/lieux/search', [LieuVenteController::class, 'filter'])->name('lieux.search');
Route::get('/lieux/{id}', [LieuVenteController::class, 'show'])->name('lieux.show');


Route::get('/panierclient', [ClientController::class, 'panierclient']);

Route::get('/send-email', [MailController::class, 'sendVerificationEmail']);

Route::get('/map',[ChauffeurController::class, "index" ]);
Route::get('/map/{id?}', [ChauffeurController::class, 'index'])->name('map');


Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('add/{id}', [CartController::class, 'add'])->name('add');
    Route::delete('remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::patch('update/{id}', [CartController::class, 'update'])->name('update');
});


Route::get('/profil/{id_client}', [ClientController::class, 'profil'])->name('profil');
Route::get('/profil/{id_client}/add-card', [CBController::class, 'create'])->name('card.create');
Route::post('/profil/{id_client}/add-card', [CBController::class, 'store'])->name('card.store');

Route::get('/profil/{id_client}/delete-card/{id_cb}', [CBController::class, 'destroy'])->name('card.delete');

Route::get('/info-compte', function() {
    return view('info-compte');
});

Route::get('/chauffeur-main', function() {
    return view('chauffeur-main');
});

Route::get('/chauffeur-propositions/{id}', [ChauffeurController::class, 'AfficherPropositions'])->name('propositions');
Route::get('/chauffeur-archives/{id}', [ChauffeurController::class, 'AfficherCoursesPassees'])->name('archives');


Route::post('/cart/add/{type}/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/panier/supprimer/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('update/{id}', [CartController::class, 'update'])->name('update');

Route::get('/panier/confirm', [CartController::class, 'passercomande'])->name('cart.confirm');

Route::get('/ajouteradresse',[ClientController::class ,'ajtadresse'])->name('ajtadresse');
Route::post('/ajouter-adresse', [ClientController::class, 'valideadresse'])->name('ajouter.adresse');
Route::delete('/supprimer-adresse/{id}', [ClientController::class, 'supprimerAdresse'])->name('supprimer.adresse');

Route::post('/panier/selection-adresse/{adresse}', [ClientController::class, 'validerAvecAdresse'])->name('valider.panier');



Route::get('/commande-list',[ClientController::class ,'voircommandes'])->name('voircommande');

Route::get('/creer-restaurant', [RestaurantController::class, 'affichercreation'])->name('affichercreation');
Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');




Route::post('add/{type}/{id}', [CartController::class, 'add'])->name('cart.add');



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


Route::post('/verification', [MailController::class, 'verifyCode'])->name('verification.submit');


Route::get('/verificationmail', [MailController::class, 'sendMail'])->name('verifiermail');
