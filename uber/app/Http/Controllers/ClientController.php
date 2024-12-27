<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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
        $this->middleware("auth");
    }

    public function panierclient(Request $request)
    {
        // Vérifier si l'utilisateur est authentifié
        if (Auth::check()) {
            // Récupérer l'ID du client connecté
            $clientId = Auth::user()->id_client;

            $paniers = DB::table("panier as p")
                ->leftJoin("contient as c", "p.id_panier", "=", "c.id_panier")
                ->leftJoin(
                    "contient_plat as cp",
                    "p.id_panier",
                    "=",
                    "cp.id_panier"
                )
                ->leftJoin(
                    "contient_menu as cm",
                    "p.id_panier",
                    "=",
                    "cm.id_panier"
                )
                ->leftJoin(
                    "produit as pr",
                    "c.id_produit",
                    "=",
                    "pr.id_produit"
                )
                ->leftJoin("plat as pl", "cp.id_plat", "=", "pl.id_plat")
                ->leftJoin("menu as m", "cm.id_menu", "=", "m.id_menu")
                ->leftJoin(
                    "commande_repas as cr",
                    "p.id_panier",
                    "=",
                    "cr.id_panier"
                )
                ->leftJoin(
                    "client as cli",
                    "cr.id_client",
                    "=",
                    "cli.id_client"
                )
                ->select(
                    "cr.id_commande_repas",
                    "p.montant",
                    "pr.nom_produit",
                    "c.quantite as quantite_produit",
                    "m.libelle_menu",
                    "cm.quantite as quantite_menu",
                    "pl.libelle_plat",
                    "cp.quantite as quantite_plat",
                    "cli.prenom_cp",
                    "cli.nom_cp"
                )
                ->where("cr.id_client", "=", $clientId)
                ->get();

            // Retourner la vue avec les paniers
            return view("panier", ["paniers" => $paniers]);
        } else {
            // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
            return redirect()
                ->route("login")
                ->with(
                    "error",
                    "Vous devez être connecté pour accéder à cette page."
                );
        }
    }
    public function profil($id)
    {
        // Récupération de l'utilisateur connecté
        $currentUser = auth()->user();

        // Vérification que l'utilisateur connecté est autorisé à accéder à cette page
        if (!$currentUser || $currentUser->id_client != $id) {
            return redirect("/")->with(
                "error",
                'Vous n\'êtes pas autorisé à accéder à cette page.'
            );
        }

        // Récupération des données du client et des cartes bancaires associées
        $client = DB::table("client as c")
            ->leftJoin("possede as p", "p.id_client", "=", "c.id_client")
            ->leftJoin("cb as cb", "cb.id_cb", "=", "p.id_cb")
            ->leftJoin(
                "se_fait_livrer_a as sf",
                "sf.id_client",
                "=",
                "c.id_client"
            )
            ->leftJoin("adresse as a", "a.id_adresse", "=", "sf.id_adresse")
            ->select(
                "c.prenom_cp",
                "c.id_client",
                "c.nom_cp",
                "c.mail_client",
                "c.tel_client",
                "cb.num_cb",
                "cb.nom_cb",
                "cb.date_fin_validite",
                "cb.type_cb",
                "cb.id_cb",
                "a.ville",
                "a.rue",
                "a.cp",
                "a.id_adresse"
            )
            ->where("c.id_client", $id)
            ->get();

        if ($client->isEmpty()) {
            return redirect("/")->with("error", "Utilisateur introuvable.");
        }

        // Retourner la vue avec les informations du client et des cartes
        return view("auth.profil", ["client" => $client]);
    }

    public function ajtadresse()
    {
        return view("client.client_ajt_adresse");
    }
    public function ajtcarte()
    {
        return view("auth.add-card");
    }

    public function valideadresse(Request $request)
    {
        // Validation des champs
        $validatedData = $request->validate(
            [
                "rue" => "required|string|max:255",
                "cp" => "required|digits:5",
                "ville" => "required|string|max:100",
            ],
            [
                "rue.required" => "Le champ rue est obligatoire.",
                "cp.required" => "Le champ code postal est obligatoire.",
                "cp.digits" =>
                    "Le code postal doit contenir exactement 5 chiffres.",
                "ville.required" => "Le champ ville est obligatoire.",
            ]
        );

        // Traitement de l'adresse
        $departement = substr($validatedData["cp"], 0, 2);
        $departement = $departement + 1;

        $adresse = new Adresse();
        $adresse->id_departement = $departement;
        $adresse->rue = $validatedData["rue"];
        $adresse->ville = $validatedData["ville"];
        $adresse->cp = $validatedData["cp"];
        $adresse->save();

        // Sauvegarde de la relation client -> adresse
        $id_adresse = $adresse->id_adresse;

        $sfla = new SeFaitLivrerA();
        $sfla->id_client = auth()->user()->id_client;
        $sfla->id_adresse = $id_adresse;
        $sfla->save();

        // Vérifier si la demande vient du profil ou du panier
        $from = $request->input("from");
        // Si 'from' est 'cart', rediriger vers la confirmation du panier
        if ($from === "cart") {
            return redirect()->route("cart.confirm"); // Redirige vers le panier
        } else {
            // Sinon, rediriger vers la page du profil
            return redirect()
                ->route("profil", ["id_client" => auth()->user()->id_client])
                ->with("success", "Adresse ajoutée avec succès");
        }
    }

    public function supprimerAdresse($id, Request $request)
    {
        $adresse = Adresse::find($id);

        if ($adresse) {
            // Supprimer la relation avec SeFaitLivrerA
            SeFaitLivrerA::where("id_adresse", $id)->delete();

            $adresse->delete();

            $from = $request->input("from");

            if ($from === "cart") {
                return redirect()
                    ->route("cart.confirm")
                    ->with(
                        "success",
                        'L\'adresse a été supprimée avec succès.'
                    );
            } else {
                return redirect()
                    ->route("profil", [
                        "id_client" => auth()->user()->id_client,
                    ])
                    ->with(
                        "success",
                        'L\'adresse a été supprimée avec succès.'
                    );
            }
        } else {
            return redirect()
                ->route("cart.confirm")
                ->with("error", "Adresse non trouvée.");
        }
    }

    public function validerPanier(Request $request)
    {
        // Récupérer les adresses de l'utilisateur
        $adresses = DB::table("adresse as a")
            ->leftJoin(
                "se_fait_livrer_a as sfla",
                "sfla.id_adresse",
                "=",
                "a.id_adresse"
            )
            ->select("a.id_adresse")
            ->where("sfla.id_client", Auth::user()->id_client)
            ->get();

        // Debug : Voir les adresses récupérées

        // Si la collection est vide (aucune adresse)
        if ($adresses->isEmpty()) {
            return redirect()
                ->route("ajtadresse", ["from" => "cart"])
                ->with(
                    "message",
                    "Veuillez ajouter une adresse avant de valider votre panier."
                );
        } elseif (count($adresses) > 1) {
            // Si l'utilisateur a exactement une adresse, l'utiliser pour valider le panier
            $adresse = $adresses[0];
            if ($adresse == null) {
                echo "<script>console.log('Ceci est un message PHP dans la console');</script>";
            }
            // Récupérer la première adresse
            else {
                $this->validerAvecAdresse($adresse, $request);
            }
        } else {
            // Si l'utilisateur a plusieurs adresses, afficher la page pour en sélectionner une
            return view("cart.selection_adresse", compact("adresses"));
        }
    }

    public function validerAvecAdresse(Request $request)
    {
        $idAdresse = $request->input("adresse");
        $idCarte = $request->input("carte");

        // Vérification de la carte bancaire
        $carte = DB::table("cb")
            ->where("id_cb", $idCarte)
            ->first();

        if (!$carte) {
            return redirect()
                ->route("cart.confirm")
                ->with("error", "La carte bancaire sélectionnée est invalide.");
        }

        // Vérification de l'adresse
        $adresse = DB::table("adresse")
            ->where("id_adresse", $idAdresse)
            ->first();

        if (!$adresse) {
            return redirect()
                ->route("cart.confirm")
                ->with("error", 'L\'adresse sélectionnée est invalide.');
        }

        // Création de la commande
        $commande = new CommandeRepas();
        $commande->id_adresse = $idAdresse;
        $commande->id_chauffeur = null;
        $commande->id_client = Auth::user()->id_client;
        $commande->type_livraison = "commande";
        $commande->horaire_livraison = null;
        $commande->temps_de_livraison = null;
        $commande->save();

        // ID de la commande
        $idCommandeRepas = $commande->id_commande_repas;

        // Récupération du panier et enregistrement des détails de commande
        $idPanier = DB::table("panier")
            ->where("id_client", Auth::user()->id_client)
            ->value("id_panier");

        if (!$idPanier) {
            return redirect()
                ->route("panier")
                ->with("error", "Aucun panier trouvé.");
        }

        $this->transfererContenuPanier($idPanier, $idCommandeRepas);

        // Redirection avec succès
        return view("cart.confirmed")->with(
            "success",
            "Votre commande a été validée avec succès."
        );
    }

    private function transfererContenuPanier($idPanier, $idCommandeRepas)
    {
        // Récupération des produits
        $produits = DB::table("contient as c")
            ->join("produit as p", "c.id_produit", "=", "p.id_produit")
            ->where("c.id_panier", $idPanier)
            ->select(
                "c.id_produit",
                "c.quantite",
                "p.nom_produit",
                "p.prix_produit"
            )
            ->get();

        // Récupération des plats
        $plats = DB::table("contient_plat as c")
            ->join("plat as p", "c.id_plat", "=", "p.id_plat")
            ->where("c.id_panier", $idPanier)
            ->select("c.id_plat", "c.quantite", "p.libelle_plat", "p.prix_plat")
            ->get();

        // Récupération des menus
        $menus = DB::table("contient_menu as c")
            ->join("menu as m", "c.id_menu", "=", "m.id_menu")
            ->where("c.id_panier", $idPanier)
            ->select("c.id_menu", "c.quantite", "m.libelle_menu", "m.prix_menu")
            ->get();

        foreach ($produits as $produit) {
            $estContenuDans = new EstContenuDans();
            $estContenuDans->id_produit = $produit->id_produit;
            $estContenuDans->id_commande_repas = $idCommandeRepas;
            $estContenuDans->quantite = $produit->quantite;
            $estContenuDans->save();
        }

        foreach ($plats as $plat) {
            $estContenuDansPlat = new EstContenuPlat();
            $estContenuDansPlat->id_plat = $plat->id_plat;
            $estContenuDansPlat->id_commande_repas = $idCommandeRepas;
            $estContenuDansPlat->quantite = $plat->quantite;
            $estContenuDansPlat->save();
        }

        foreach ($menus as $menu) {
            $estContenuDansMenu = new EstContenuDansMenu();
            $estContenuDansMenu->id_menu = $menu->id_menu;
            $estContenuDansMenu->id_commande_repas = $idCommandeRepas;
            $estContenuDansMenu->quantite = $menu->quantite;
            $estContenuDansMenu->save();
        }
    }

    public function terminer($id)
    {
        $course = Course::findOrFail($id);
        $terminee = "true";
        echo "<script>console.log(" . $course . ")</script>";

        $validationChauffeur = DB::table("course")
            ->where("id_course", $course->id_course)
            ->value("validationchauffeur");

        \DB::table("course")
            ->where("id_course", $course->id_course)
            ->update([
                "validationclient" => $terminee,
            ]);

        if (json_encode($validationChauffeur) == "true") {
            \DB::table("course")
                ->where("id_course", $course->id_course)
                ->update([
                    "terminee" => $terminee,
                ]);
        }

        echo "<script>console.log(" . $course . ")</script>";
        $chauffeurId = $course->id_chauffeur;

        echo $chauffeurId;

        $chauffeurController = new ChauffeurController();

        return redirect()
            ->route("courses.index")
            ->with("success", "Course terminée avec succès.");
    }

    public function voircommandes()
    {
        $clientId = Auth::user()->id_client;

        // Récupération des données du client et des cartes bancaires associées
        $commandes = DB::table("commande_repas as cr")
            ->leftJoin(
                "est_contenu_dans as ecd",
                "ecd.id_commande_repas",
                "=",
                "cr.id_commande_repas"
            )
            ->leftJoin("produit as p", "ecd.id_produit", "=", "p.id_produit")
            ->leftJoin(
                "est_contenu_dans_menu as ecdm",
                "ecdm.id_commande_repas",
                "=",
                "cr.id_commande_repas"
            )
            ->leftJoin("menu as m", "ecdm.id_menu", "=", "m.id_menu")
            ->leftJoin(
                "est_contenu_dans_plat as ecdp",
                "ecdp.id_commande_repas",
                "=",
                "cr.id_commande_repas"
            )
            ->leftJoin("plat as pl", "ecdp.id_plat", "=", "pl.id_plat")
            ->where("cr.id_client", "=", $clientId)
            ->select(
                "cr.id_commande_repas",
                "pl.libelle_plat",
                "ecdp.quantite as quantite_plat",
                "pl.prix_plat",
                "p.nom_produit",
                "ecd.quantite as quantite_produit",
                "p.prix_produit",
                "m.libelle_menu",
                "ecdm.quantite as quantite_menu",
                "m.prix_menu"
            )
            ->get()
            ->groupBy("id_commande_repas"); // Grouper par commande

        // Retourner la vue avec les commandes regroupées
        return view("commande-list", ["commandes" => $commandes]);
    }

    public function CreerRestaurant()
    {
        // Récupérer toutes les catégories disponibles
        $categories = DB::table("categorie_restaurant")->get();

        return view("professionnel-creation-restaurant", compact("categories"));
    }

    public function edit()
    {
        $client = Auth::user(); 
        return view('client.edit', compact('client'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|numeric',
        ]);

        $client = Auth::user();
        $client->update([
            'mail_client' => $request->email,
            'nom_cp' => $request->nom,
            'prenom_cp' => $request->prenom,
            'tel_client' => $request->telephone,
        ]);

        return redirect()->route('profil', ['id_client' => $client->id_client])
            ->with('success', 'Vos informations ont été mises à jour.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'pp_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $client = Auth::user();

        if ($client->photo && $client->photo !== 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png') {
            Storage::disk('public')->delete($client->photo);
        }

        $path = $request->file('pp_img')->store('profile_photos', 'public');
        $client->update(['photo' => $path]);

        return back()->with('success', 'Votre photo de profil a été mise à jour.');
    }
}
