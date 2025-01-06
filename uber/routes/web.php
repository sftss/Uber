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
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\SmsController;

Route::get('/', function () {
    return view('main');
})->name('home');

Route::post('/planifier-rdv/{chauffeur_id}', [ChauffeurController::class, 'planifierRdv'])->name('planifier-rdv');

Route::post('/changer-statuts-rdv/{chauffeur_id}', [ChauffeurController::class, 'changerStatutRdv'])->name('changer-statuts-rdv');

Route::get('/test-mail', [MailController::class, 'sendMail']);

Route::get('/courses', [CourseController::class, 'index'])->middleware('auth')->name('courses.index');
Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

Route::put('/courses/{id}', [ChauffeurController::class, 'index'])->name('courses.update');

Route::put('/courses/refuser/{course}', [CourseController::class, 'refuser'])->name('courses.refuser');
Route::put('/courses/accepter/{course}', [CourseController::class, 'accepter'])->name('courses.accepter');

Route::get('/chauffeur-propositions/{id}', [ChauffeurController::class, 'AfficherPropositions'])->name('propositions');
Route::get('/chauffeur-archives/{id}', [ChauffeurController::class, 'AfficherCoursesPassees'])->name('archives');
Route::get('/chauffeur-a-venir/{id}', [ChauffeurController::class, 'AfficherCoursesAVenir'])->name('a_venir');

// Route::get('/chauffeur/{id}/propositions', [ChauffeurController::class, 'AfficherPropositions'])->name('chauffeur.propositions');
Route::put('/course/{id}/accepter', [CourseController::class, 'accepter'])->name('courses.accepter');
Route::put('/course/{id}/refuser', [CourseController::class, 'refuser'])->name('courses.refuser');
Route::put('/chauffeur/{id}/terminer', [ChauffeurController::class, 'terminer'])->name('chauffeur.terminer');


Route::put('/courses/terminer-chauffeur/{course}', [ChauffeurController::class, 'terminer'])->name('chauffeur.terminer');
Route::put('/courses/terminer-client/{course}', [ClientController::class, 'terminer'])->name('client.terminer');

Route::put('/courses/{id}/terminer', [CourseController::class, 'terminer'])->name('client.terminer');
Route::post('/courses/{courseId}/review', [CourseController::class, 'submitReview'])->name('courses.submitReview');

Route::post('/courses/{id_course}/review', [CourseController::class, 'submitReview'])->name('courses.submitReview');

// Route::get('/facture', [FactureController::class, 'genererFacture']);
Route::post('/courses/{id_course}/Facture', [FactureController::class, 'genererFacture'])
    ->name('courses.Facture');
Route::post('/courses/{id_course}/facture', [FactureController::class, 'genererFacture'])->name('courses.Facture'); //lang facture

Route::get('/restaurants/filter', [RestaurantController::class, 'filter'])->name('restaurants.filter');

Route::get('/restaurants/search', [RestaurantController::class, 'filter'])->name('restaurants.search');
Route::get('/restaurants/search/mesrestaurants', [RestaurantController::class, 'filtermoi'])->name('restaurants.searchmoi');

Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('/restaurants/{id}/commandes', [RestaurantController::class, 'affichercommandes'])->name('restaurants.affichercommandes');
Route::post('/restaurants/{id}/commandes/attribuer-chauffeur', [RestaurantController::class, 'attribuerChauffeur'])->name('restaurants.attribuerChauffeur');

Route::get('/lieux/{id}/commandes', [LieuVenteController::class, 'affichercommandes'])->name('lieux.affichercommandes');
Route::post('/lieux/{id}/commandes/attribuer-chauffeur', [LieuVenteController::class, 'attribuerChauffeur'])->name('lieux.attribuerChauffeur');


Route::get('/lieux/search', [LieuVenteController::class, 'filter'])->name('lieux.search');
Route::get('/lieux/{id}', [LieuVenteController::class, 'show'])->name('lieux.show');
Route::get('/professionnel/lieux/create', [LieuVenteController::class, 'create'])->name('lieux.create');
Route::post('/professionnel/lieux', [LieuVenteController::class, 'store'])->name('lieux.store');


Route::get('/send-email', [MailController::class, 'sendVerificationEmail']);

Route::get('/map',[ChauffeurController::class, "index" ]);
Route::get('/map/{id?}', [ChauffeurController::class, 'index'])->name('map');


Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('add/{id}', [CartController::class, 'add'])->name('add');
    Route::delete('remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::patch('update/{id}', [CartController::class, 'update'])->name('update');
});

Route::post('/profil/update-photo', [ClientController::class, 'updatePhoto'])->name('client.updatePhoto');

Route::get('/profil/{id_client}', [ClientController::class, 'profil'])->name('profil');
Route::get('/profil/{id_client}/add-card', [CBController::class, 'create'])->name('card.create');
Route::post('/profil/{id_client}/add-card', [CBController::class, 'store'])->name('card.store');

Route::get('/profil/{id_client}/delete-card/{id_cb}', [CBController::class, 'destroy'])->name('card.delete');

Route::get('/client', function() {
    return view('client');
});
Route::get('/client/edit', [ClientController::class, 'edit'])->name('compte.edit');
Route::post('/client/edit', [ClientController::class, 'update'])->name('compte.update');
Route::get('/client/password/edit', [ClientController::class, 'editPassword'])->name('password.edit');
Route::post('/client/password/update', [ClientController::class, 'updatePassword'])->name('password.update');


Route::get('/chauffeur-main', function() {
    return view('chauffeur/chauffeur-main');
});

Route::get('/service-course', function() {
    return view('servicecourse/servicecourse-main');
});

Route::get('/voircourse', function() {
    return view('servicecourse/voircourse');
});


Route::get('/service-rh', function() {
    return view('rh/rh-main');
});

Route::get('/voirchauffeur', function() {
    return view('rh/voirchauffeur');
});

Route::get('/chauffeurs-a-valider', [ChauffeurController::class, 'AfficherChauffeurAValider'])->name('afficher-chauffeur-a-valider');

Route::match(['GET', 'POST'], '/traitement', [AdresseController::class, 'traitement'])->name('traitement');

Route::post('/trouverchauffeurs', [ChauffeurController::class, 'trouverChauffeurs'])->name('trouverchauffeurs');

Route::get('/professionnel-main', function() {
    return view('/professionnel/professionnel-main');
});

Route::get('/professionnel-restaurants/{id}', [ClientController::class, 'AfficherRestaurants'])->name('restaurants');
// Route::get('/professionnel-creation/restaurant/{id}', [ClientController::class, 'CreerRestaurant'])->name('creation.restaurant');


Route::post('/cart/add/{type}/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/panier/supprimer/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('update/{id}', [CartController::class, 'update'])->name('update');

Route::get('/panierclient', [ClientController::class, 'panierclient']);
Route::get('/panier/confirm', [CartController::class, 'passercomande'])->name('cart.confirm');
Route::post('/panier/valider', [ClientController::class, 'validerAvecAdresse'])->name('valider.panier');

Route::get('/ajoutercarte',[ClientController::class ,'ajtcarte'])->name('ajtcarte');

Route::get('/ajouteradresse',[ClientController::class ,'ajtadresse'])->name('ajtadresse');
Route::post('/ajouter-adresse', [ClientController::class, 'valideadresse'])->name('ajouter.adresse');
Route::delete('/supprimer-adresse/{id}', [ClientController::class, 'supprimerAdresse'])->name('supprimer.adresse');


Route::get('/commande-list',[ClientController::class ,'voircommandes'])->name('voircommande');

Route::get('/creer-restaurant', [RestaurantController::class, 'create'])->name('create');
Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');

Route::get('/restaurant/{restaurant_id}/produit/create', [ProduitController::class, 'create'])->name('produit.create');
Route::post('produit/store', [ProduitController::class, 'store'])->name('produit.store');

Route::get('/restaurant/{restaurant_id}/plat/create', [PlatController::class, 'create'])->name('plat.create');
Route::post('plat/store', [PlatController::class, 'store'])->name('plat.store');

Route::get('restaurant/{restaurant_id}/menu/create', [MenuController::class, 'create'])->name('menu.create');
Route::post('menu/store', [MenuController::class, 'store'])->name('menu.store');

Route::get('/lieux/{lieu_id}/produit/create', [ProduitController::class, 'createForLieu'])->name('lieux.produit.create');
Route::post('/lieux/{lieu_id}/produit/store', [ProduitController::class, 'storeForLieu'])->name('lieux.produit.store');


Route::post('add/{type}/{id}', [CartController::class, 'add'])->name('cart.add');

// Afficher le formulaire de sélection du type d'inscription
Route::get('pageinscription', [RegisterController::class, 'showRegistrationSelection'])->name('register.selection');

// Formulaire d'inscription pour les clients
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('register', [RegisterController::class, 'register'])->name('register');

// Formulaire d'inscription pour les chauffeurs
Route::get('registerch', [RegisterController::class, 'showRegistrationFormch'])->name('register.formch');
Route::post('registerch', [RegisterController::class, 'registerch'])->name('registerch');


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('loginch', [LoginController::class, 'showLoginFormch'])->name('loginch');
Route::post('loginch', [LoginController::class, 'loginch'])->name('login.postch');
Route::post('logoutch', [LoginController::class, 'logoutch'])->name('logoutch');
// sélection connexion
Route::get('pageconnexion', [LoginController::class, 'showLoginSelection'])->name('login.selection');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('loginch', [LoginController::class, 'showLoginFormCh'])->name('loginch');
Route::get('loginService', [LoginController::class, 'showLoginServiceForm'])->name('loginService');
Route::get('loginservice', [LoginController::class, 'showLoginServiceForm'])->name('loginService');


Route::get('/politique', function () {
    return view('help/politique');
})->name('politique');

Route::get('confirm-email/{code}', [RegistrationController::class, 'confirmEmail'])->name('confirm.email');


Route::get('/adresse/{id}', [AdresseController::class, 'show']);

Route::get('/verify', [RegisterController::class, 'showVerificationForm'])->name('verify.form');
Route::post('/verify', [RegisterController::class, 'verifyCode'])->name('verify.code');

Route::get('/verification', [VerificationController::class, 'showVerificationPage'])->name('verification.page');


Route::post('/verification', [MailController::class, 'verifyCode'])->name('verification.submit');


Route::get('/verificationmail', [MailController::class, 'sendMail'])->name('verifiermail');

Route::get('/confirmpaiement', [MailController::class, 'sendMailPaiement'])->name('mailpaiement');

Route::get('/envoi-sms', [SmsController::class, 'sendSms']);


Route::get('/help', function () {
    return view('help.aide');
})->name('help');