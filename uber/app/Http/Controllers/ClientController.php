<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Adresse;
use App\Models\SeFaitLivrerA;
use App\Models\CommandeRepas;
use App\Models\EstContenuDansMenu;
use App\Models\EstContenuPlat;
use App\Models\EstContenuDans;
use App\Models\Course;


class ClientController extends Controller
{
    // Ajouter un middleware pour s'assurer que l'utilisateur est authentifié
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function panierclient(Request $request)
    {
        // Vérifier si l'utilisateur est authentifié
        if (Auth::check()) {
            // Récupérer l'ID du client connecté
            $clientId = Auth::user()->id_client;

            $paniers = DB::table('panier as p')
            ->leftJoin('contient as c', 'p.id_panier', '=', 'c.id_panier')
            ->leftJoin('contient_plat as cp', 'p.id_panier', '=', 'cp.id_panier')
            ->leftJoin('contient_menu as cm', 'p.id_panier', '=', 'cm.id_panier')
            ->leftJoin('produit as pr', 'c.id_produit', '=', 'pr.id_produit')
            ->leftJoin('plat as pl', 'cp.id_plat', '=', 'pl.id_plat')
            ->leftJoin('menu as m', 'cm.id_menu', '=', 'm.id_menu')
            ->leftJoin('commande_repas as cr', 'p.id_panier', '=', 'cr.id_panier')
            ->leftJoin('client as cli', 'cr.id_client', '=', 'cli.id_client')
            ->select(
                'cr.id_commande_repas', 'p.montant', 'pr.nom_produit', 'c.quantite as quantite_produit', 
                'm.libelle_menu', 'cm.quantite as quantite_menu', 'pl.libelle_plat', 'cp.quantite as quantite_plat',
                'cli.prenom_cp', 'cli.nom_cp'
            )
            ->where('cr.id_client', '=', $clientId)
            ->get();

        

            // Retourner la vue avec les paniers
            return view('panier', ['paniers' => $paniers]);
        } else {
            // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
    }
    public function profil($id)
{
    // Récupération de l'utilisateur connecté
    $currentUser = auth()->user();

    // Vérification que l'utilisateur connecté est autorisé à accéder à cette page
    if (!$currentUser || $currentUser->id_client != $id) {
        return redirect('/')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
    }

    // Récupération des données du client et des cartes bancaires associées
    $client = DB::table('client as c')
        ->leftJoin('possede as p', 'p.id_client', '=', 'c.id_client')
        ->leftJoin('cb as cb', 'cb.id_cb', '=', 'p.id_cb')
        ->leftJoin('se_fait_livrer_a as sf','sf.id_client','=','c.id_client')
        ->leftJoin('adresse as a','a.id_adresse','=','sf.id_adresse')
        ->select('c.prenom_cp', 'c.id_client','c.nom_cp', 'c.mail_client', 'c.tel_client', 'cb.num_cb', 'cb.nom_cb', 'cb.date_fin_validite','cb.type_cb','cb.id_cb','a.ville','a.rue','a.cp','a.id_adresse')
        ->where('c.id_client', $id)
        ->get();

    // Vérification que le client existe
    if ($client->isEmpty()) {
        return redirect('/')->with('error', 'Utilisateur introuvable.');
    }

    // Retourner la vue avec les informations du client et des cartes
    return view('auth.profil', ['client' => $client]);
}


    public function ajtadresse()
    {
        return view('client.client_ajt_adresse');
    }

    public function valideadresse(Request $request)
{
    // Validation des champs
    $validatedData = $request->validate([
        'rue' => 'required|string|max:255',
        'cp' => 'required|digits:5',
        'ville' => 'required|string|max:100',
    ], [
        'rue.required' => 'Le champ rue est obligatoire.',
        'cp.required' => 'Le champ code postal est obligatoire.',
        'cp.digits' => 'Le code postal doit contenir exactement 5 chiffres.',
        'ville.required' => 'Le champ ville est obligatoire.',
    ]);

    // Traitement de l'adresse
    $departement = substr($validatedData['cp'], 0, 2);
    $departement = $departement + 1;

    $adresse = new Adresse();
    $adresse->id_departement = $departement;
    $adresse->rue = $validatedData['rue'];
    $adresse->ville = $validatedData['ville'];
    $adresse->cp = $validatedData['cp'];
    $adresse->save();

    // Sauvegarde de la relation client -> adresse
    $id_adresse = $adresse->id_adresse;

    $sfla = new SeFaitLivrerA();
    $sfla->id_client = auth()->user()->id_client;
    $sfla->id_adresse = $id_adresse;
    $sfla->save();

    // Vérifier si la demande vient du profil ou du panier
    $from = $request->input('from');
    // Si 'from' est 'cart', rediriger vers la confirmation du panier
    if ($from === 'cart') {
        return redirect()->route('cart.confirm'); // Redirige vers le panier
    } else {
        // Sinon, rediriger vers la page du profil
        return redirect()->route('profil', ['id_client' => auth()->user()->id_client])->with('success', 'Adresse ajoutée avec succès');
    }
}



public function supprimerAdresse($id, Request $request)
{
    // Trouver l'adresse avec l'ID
    $adresse = Adresse::find($id);

    // Vérifier si l'adresse existe et la supprimer
    if ($adresse) {
        // Supprimer la relation avec SeFaitLivrerA
        SeFaitLivrerA::where('id_adresse', $id)->delete();

        // Supprimer l'adresse
        $adresse->delete();

        // Vérifier d'où provient la requête (le paramètre 'from')
        $from = $request->input('from');

        // Si 'from' est 'cart', rediriger vers la confirmation du panier
        if ($from === 'cart') {
            return redirect()->route('cart.confirm')->with('success', 'L\'adresse a été supprimée avec succès.');
        } else {
            // Sinon, rediriger vers la page du profil
            return redirect()->route('profil', ['id_client' => auth()->user()->id_client])->with('success', 'L\'adresse a été supprimée avec succès.');
        }
    } else {
        // Si l'adresse n'existe pas, rediriger avec un message d'erreur
        return redirect()->route('cart.confirm')->with('error', 'Adresse non trouvée.');
    }
}


public function validerPanier(Request $request)
{
    // Récupérer les adresses de l'utilisateur
    $adresses = DB::table('adresse as a')
        ->leftJoin('se_fait_livrer_a as sfla', 'sfla.id_adresse', '=', 'a.id_adresse')
        ->select('a.id_adresse')
        ->where('sfla.id_client', Auth::user()->id_client)
        ->get();

    // Debug : Voir les adresses récupérées


    // Si la collection est vide (aucune adresse)
    if ($adresses->isEmpty()) {
        return redirect()->route('ajtadresse', ['from' => 'cart'])->with('message', 'Veuillez ajouter une adresse avant de valider votre panier.');
    } elseif (count($adresses)>1) {
        // Si l'utilisateur a exactement une adresse, l'utiliser pour valider le panier
        $adresse = $adresses[0]; 
        if($adresse == null){
            echo "<script>console.log('Ceci est un message PHP dans la console');</script>";
        } // Récupérer la première adresse
        else {
            $this->validerAvecAdresse($adresse, $request);
        }
    } else {
        // Si l'utilisateur a plusieurs adresses, afficher la page pour en sélectionner une
        return view('cart.selection_adresse', compact('adresses'));
    }
}


public function validerAvecAdresse($adresse, Request $request)
{

 

    // Créer une commande
    $commande = new CommandeRepas();
    $commande->id_adresse = $adresse;
    $commande->id_chauffeur = null;
    $commande->id_client = Auth::user()->id_client;
    $commande->type_livraison = 'commande';
    $commande->horaire_livraison = null;
    $commande->temps_de_livraison = null;
    $commande->save();

    // ID de la commande
    $idcommanderepas = $commande->id_commande_repas;

    // Récupérer le panier de l'utilisateur
    $idpanier = DB::table('panier as p')
        ->select('p.id_panier')
        ->where('p.id_client', Auth::user()->id_client)
        ->first();

    if (!$idpanier) {
        return redirect()->route('panier')->with('error', 'Aucun panier trouvé.');
    }

    // Récupérer les produits, plats et menus du panier
    $produits = DB::table('contient as c')
        ->join('produit as p', 'c.id_produit', '=', 'p.id_produit')
        ->where('c.id_panier', $idpanier->id_panier)
        ->select('c.id_produit', 'c.quantite', 'p.nom_produit', 'p.prix_produit')
        ->get();

    $plats = DB::table('contient_plat as c')
        ->join('plat as p', 'c.id_plat', '=', 'p.id_plat')
        ->where('c.id_panier', $idpanier->id_panier)
        ->select('c.id_plat', 'c.quantite', 'p.libelle_plat', 'p.prix_plat')
        ->get();

    $menus = DB::table('contient_menu as c')
        ->join('menu as m', 'c.id_menu', '=', 'm.id_menu')
        ->where('c.id_panier', $idpanier->id_panier)
        ->select('c.id_menu', 'c.quantite', 'm.libelle_menu', 'm.prix_menu')
        ->get();

        
    foreach ($produits as $produit) {
        $estcontenudans = new EstContenuDans();
        $estcontenudans->id_produit = $produit->id_produit;
        $estcontenudans->id_commande_repas = $idcommanderepas;
        $estcontenudans->quantite = $produit->quantite;
        $estcontenudans->save();
    }

    // Enregistrer les plats dans la table 'EstContenuDansPlat'
    foreach ($plats as $plat) {
        $estcontenudansplat = new EstContenuPlat();
        $estcontenudansplat->id_plat = $plat->id_plat;
        $estcontenudansplat->id_commande_repas = $idcommanderepas;
        $estcontenudansplat->quantite = $plat->quantite;
        $estcontenudansplat->save();
    }

    // Enregistrer les menus dans la table 'EstContenuDansMenu'
    foreach ($menus as $menu) {
        $estcontenudansmenu = new EstContenuDansMenu();
        $estcontenudansmenu->id_menu = $menu->id_menu;
        $estcontenudansmenu->id_commande_repas = $idcommanderepas;
        $estcontenudansmenu->quantite = $menu->quantite;
        $estcontenudansmenu->save();
    }
    // Rediriger vers la page de confirmation

    
    




    return view('cart.confirmed');
}

public function terminer($id) {
    $course = Course::findOrFail($id);
    $terminee = "true";
    echo "<script>console.log(".$course.")</script>";

    $validationChauffeur= DB::table('course')
                  ->where('id_course', $course->id_course)
                  ->value('validationchauffeur'); 

    
    \DB::table('course')
    ->where('id_course', $course->id_course)
    ->update([
        'validationclient' => $terminee
    ]);
    
    if (json_encode($validationChauffeur) == "true")
    {
        \DB::table('course')
    ->where('id_course', $course->id_course)
    ->update([
        'terminee' => $terminee
    ]);
    }
    
    
    echo "<script>console.log(".$course.")</script>";
    $chauffeurId = $course->id_chauffeur;

    echo $chauffeurId;

    $chauffeurController = new ChauffeurController();

    return redirect()->route('courses.index')->with('success', 'Course terminée avec succès.');
}


public function voircommandes(){
    $currentUser = auth()->user();

    $clientId = Auth::user()->id_client;




    // Récupération des données du client et des cartes bancaires associées
    $client = DB::table('client as c')
    ->leftJoin('commande_repas as cr', 'cr.id_client', '=', 'c.id_client')
    ->leftJoin('est_contenu_dans as ecd', 'ecd.id_commande_repas', '=', 'cr.id_commande_repas')
    ->leftJoin('produit as p', 'ecd.id_produit', '=', 'p.id_produit')
    ->leftJoin('est_contenu_dans_menu as ecdm', 'ecdm.id_commande_repas', '=', 'cr.id_commande_repas')
    ->leftJoin('menu as m', 'ecdm.id_menu', '=', 'm.id_menu')
    ->leftJoin('est_contenu_dans_plat as ecdp', 'ecdp.id_commande_repas', '=', 'cr.id_commande_repas')
    ->leftJoin('plat as pl', 'ecdp.id_plat', '=', 'pl.id_plat')
    ->select(
        'cr.id_commande_repas',
        'pl.libelle_plat',
        'ecdp.quantite as quantite_plat',
        'pl.prix_plat as prix_plat', // Correction : Utilisation de pl pour les plats
        'p.nom_produit',
        'ecd.quantite as quantite_produit',
        'p.prix_produit as prix_produit', // Correction : Utilisation de p pour les produits
        'm.libelle_menu',
        'ecdm.quantite as quantite_menu',
        'm.prix_menu as prix_menu' // Correction : Utilisation de m pour les menus
    )
    ->where('cr.id_client', '=', $clientId)
    ->get();



    if ($client->isEmpty()) {
        return redirect('/')->with('error', 'Utilisateur introuvable.');
    }


    // Retourner la vue avec les informations du client et des cartes
    return view('commande-list', ['client' => $client]);
}



}
