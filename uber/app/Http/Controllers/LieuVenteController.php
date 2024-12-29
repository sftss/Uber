<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Adresse;
use App\Models\LieuVente;
use Illuminate\Support\Facades\Auth;

class LieuVenteController extends Controller
{
    public function filter(Request $request) {
        $recherche = $request->input('lieu');
        $livre = $request->boolean('livre'); 
        $horraire = $request->input('horaire-selected');
            
        $horaireOuverture = null;
        $horaireFermeture = null;

        if ($horraire) {
            [$horaireOuverture, $horaireFermeture] = array_map(
                fn($time) => date('H:i:s', strtotime(trim($time))),
                explode(' - ', $horraire)
            );
        }

        $lieux = DB::table('lieu_de_vente_pf')
        
        ->join('adresse', 'lieu_de_vente_pf.id_adresse', '=', 'adresse.id_adresse')
        ->when($recherche, function ($query, $recherche) {

            return $query->where(function ($q) use ($recherche) {
                $q->whereRaw('LOWER(adresse.ville) LIKE LOWER(?)', ['%' . $recherche . '%'])
                ->orWhereRaw('LOWER(lieu_de_vente_pf.nom_etablissement) LIKE LOWER(?)', ['%' . $recherche . '%']);
            });
        })
        ->when($livre , function ($query) use ($livre) {
            $query->where(function ($q) use ($livre) {
                if ($livre) {
                    $q->where('lieu_de_vente_pf.propose_livraison', 'true');
                }
            });
        })
        ->when($horaireOuverture && $horaireFermeture, function ($query) use ($horaireOuverture, $horaireFermeture) {
            return $query->where(function ($q) use ($horaireOuverture, $horaireFermeture) {
                $q->whereRaw('lieu_de_vente_pf.horaires_ouverture <= ?', [$horaireOuverture])
                ->whereRaw('lieu_de_vente_pf.horaires_fermeture >= ?', [$horaireFermeture]);
            });
        })
        
        ->select('lieu_de_vente_pf.*', 'adresse.ville','lieu_de_vente_pf.horaires_ouverture', 'lieu_de_vente_pf.horaires_fermeture')
        ->distinct()
        ->get();

        return view('lieux.filter', compact('lieux'));
    }

    public function show($id_lieu_de_vente_pf, Request $request) {
        // Récupérer le lieu de vente
        $lieu = DB::table('lieu_de_vente_pf')
            ->join('adresse', 'lieu_de_vente_pf.id_adresse', '=', 'adresse.id_adresse')
            ->select('id_lieu_de_vente_pf', 'nom_etablissement', 'horaires_ouverture', 'horaires_fermeture', 'description_etablissement', 'propose_livraison', 'photo_lieu', 'adresse.ville', 'adresse.rue', 'adresse.cp')
            ->where('id_lieu_de_vente_pf', $id_lieu_de_vente_pf)
            ->first();

        if (!$lieu) {
            abort(404, 'Lieu de vente non trouvé');
        }

        // Récupérer la liste des catégories
        $categories = DB::table('categorie_produit')->get();

        $produitRecherche = $request->input('produit'); // Nom du produit recherché
        $categorieRecherche = $request->input('categorie'); // Catégorie sélectionnée

        // Construire la requête pour récupérer les produits
        $produits = DB::table('est_vendu')
            ->join('produit', 'est_vendu.id_produit', '=', 'produit.id_produit')
            ->join('categorie_produit', 'produit.id_categorie_produit', '=', 'categorie_produit.id_categorie_produit')
            ->select('produit.id_produit', 'produit.nom_produit', 'produit.prix_produit', 'produit.photo_produit', 'categorie_produit.libelle_categorie')
            ->where('est_vendu.id_lieu_de_vente_pf', $id_lieu_de_vente_pf);

        if ($produitRecherche) {
            $produits->whereRaw('LOWER(produit.nom_produit) LIKE LOWER(?)', ['%' . $produitRecherche . '%']);
        }

        if ($categorieRecherche) {
            $produits->where('produit.id_categorie_produit', $categorieRecherche);
        }

        $produits = $produits->get();

        return view('lieux.show', compact('lieu', 'produits', 'categories'));
    }

    public function create() {
        $adresses = DB::table('adresse')->get();
        return view('professionnel.professionnel-creation-lieu_de_vente', compact('adresses'));
    }

    public function store(Request $request) {
        DB::transaction(function () use ($request) {
            $validatedData = $request->validate([
            'rue' => 'required|string|max:255',
            'cp' => 'required|digits:5',
            'ville' => 'required|string|max:100',
            'nom_etablissement' => 'nullable|string|max:50',
            'description_etablissement' => 'nullable|string',
            'horaires_ouverture' => 'nullable|date_format:H:i',
            'horaires_fermeture' => 'nullable|date_format:H:i',
            'propose_livraison' => 'nullable|boolean',
            'photo_lieu' => 'required|string|max:500',
        ]);

        $departement = substr($validatedData['cp'], 0, 2) + 1;

        $adresse = Adresse::create([
            'id_departement' => $departement,
            'rue' => $validatedData['rue'],
            'ville' => $validatedData['ville'],
            'cp' => $validatedData['cp'],
        ]);
                
        $lieuVente = LieuVente::create([
            'nom_etablissement' => $validatedData['nom_etablissement'],
            'description_etablissement' => $validatedData['description_etablissement'],
            'propose_livraison' => $request->has('propose_livraison') ? 1 : 0,
            // 'propose_retrait' => $request->has('propose_retrait') ? 1 : 0,
            'horaires_ouverture' => $validatedData['horaires_ouverture'],
            'horaires_fermeture' => $validatedData['horaires_fermeture'],
            'id_adresse' => $adresse->id_adresse,
            'photo_lieu' => $validatedData['photo_lieu'],
            'id_proprietaire' => Auth::user()->id_client,
        ]);

        });

        return redirect()->route('lieux.search')->with('success', 'Lieu de vente créé avec succès.');
    }
}
