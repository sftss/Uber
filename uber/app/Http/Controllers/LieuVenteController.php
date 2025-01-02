<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Adresse;
use App\Models\LieuVente;
use App\Models\Jour;
use App\Models\HorairesLieuVente;
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
            ->join('horaires_lieu_vente', 'lieu_de_vente_pf.id_lieu_de_vente_pf', '=', 'horaires_lieu_vente.id_lieu_de_vente_pf')
            ->when($recherche, function ($query, $recherche) {
                return $query->where(function ($q) use ($recherche) {
                    $q->whereRaw('LOWER(adresse.ville) LIKE LOWER(?)', ['%' . $recherche . '%'])
                    ->orWhereRaw('LOWER(lieu_de_vente_pf.nom_etablissement) LIKE LOWER(?)', ['%' . $recherche . '%']);
                });
            })
            ->when($livre, function ($query) use ($livre) {
                $query->where('lieu_de_vente_pf.propose_livraison', $livre);
            })
            ->when($horaireOuverture && $horaireFermeture, function ($query) use ($horaireOuverture, $horaireFermeture) {
                return $query->where(function ($q) use ($horaireOuverture, $horaireFermeture) {
                    $q->where('horaires_lieu_vente.horaires_ouverture', '<=', $horaireOuverture)
                    ->where('horaires_lieu_vente.horaires_fermeture', '>=', $horaireFermeture);
                });
            })
            ->select('lieu_de_vente_pf.*', 'adresse.ville', 'horaires_lieu_vente.horaires_ouverture', 'horaires_lieu_vente.horaires_fermeture')
            ->distinct()
            ->get();

            return view('lieux.filter', compact('lieux'));
    }

    public function show($id_lieu_de_vente_pf, Request $request) {
        $lieu = LieuVente::with(['horaires.jour', 'adresse'])
            ->where('id_lieu_de_vente_pf', $id_lieu_de_vente_pf)
            ->firstOrFail();
        $horaires = HorairesLieuVente::join('jour', 'horaires_lieu_vente.id_jour', '=', 'jour.id_jour')
            ->where('horaires_lieu_vente.id_lieu_de_vente_pf', $id_lieu_de_vente_pf)
            ->select('jour.lib_jour', 'horaires_lieu_vente.horaires_ouverture', 'horaires_lieu_vente.horaires_fermeture')
            ->orderBy('jour.id_jour')
            ->get();
        $categories = DB::table('categorie_produit')->get();
        $produitRecherche = $request->input('produit');
        $categorieRecherche = $request->input('categorie');
        $produits = DB::table('est_vendu')->join('produit', 'est_vendu.id_produit', '=', 'produit.id_produit')
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

        return view('lieux.show', compact('lieu', 'horaires', 'produits', 'categories'));
    }

    public function create() {
        $adresses = DB::table('adresse')->get();
        $jours = Jour::all();
        return view('professionnel.professionnel-creation-lieu_de_vente', compact('adresses', 'jours'));
    }

    public function store(Request $request) {
        Log::info('Début ');

        try {
            Log::info('Données POST :', $request->all());

            $validatedData = $request->validate([
                'rue' => 'required|string|max:255',
                'cp' => 'required|digits:5',
                'ville' => 'required|string|max:100',
                'nom_etablissement' => 'nullable|string|max:50',
                'description_etablissement' => 'nullable|string',
                'horaires' => 'required|array',
                'horaires.*.ouverture' => 'nullable|date_format:H:i',
                'horaires.*.fermeture' => 'nullable|date_format:H:i',
                'horaires.*.ferme' => 'nullable|boolean',
                'photo_lieu' => 'required|string|max:500',
            ]);

            Log::info('Données validées', $validatedData);

            foreach ($validatedData['horaires'] as $jourId => $horaire) {
                if (isset($horaire['ouverture'], $horaire['fermeture']) && $horaire['ouverture'] >= $horaire['fermeture']) {
                    throw ValidationException::withMessages([
                        "horaires.{$jourId}.ouverture" => "L'heure d'ouverture doit précéder l'heure de fermeture pour le jour {$jourId}.",
                    ]);
                }
            }

            $departement = substr($validatedData['cp'], 0, 2);
            Log::info('Département créé', ['departement' => $departement]);

            $adresse = Adresse::create([
                'id_departement' => $departement,
                'rue' => $validatedData['rue'],
                'ville' => $validatedData['ville'],
                'cp' => $validatedData['cp'],
            ]);
            Log::info('Adresse créée', ['adresse' => $adresse]);

            $lieuVente = LieuVente::create([
                'nom_etablissement' => $validatedData['nom_etablissement'],
                'description_etablissement' => $validatedData['description_etablissement'],
                'propose_livraison' => $request->has('propose_livraison') ? 1 : 0,
                'id_adresse' => $adresse->id_adresse,
                'photo_lieu' => $validatedData['photo_lieu'],
                'id_proprietaire' => Auth::user()->id_client,
            ]);
            Log::info('Lieu de vente créé', ['lieu_vente' => $lieuVente]);

            foreach ($validatedData['horaires'] as $jourId => $horaire) {
                HorairesLieuVente::create([
                    'id_jour' => $jourId,
                    'id_lieu_de_vente_pf' => $lieuVente->id_lieu_de_vente_pf,
                    'horaires_ouverture' => $horaire['ouverture'] ?? null,
                    'horaires_fermeture' => $horaire['fermeture'] ?? null,
                    'ferme' => isset($horaire['ferme']) ? 1 : 0,
                ]);
            }

            Log::info('Transaction terminée');
        } catch (\Exception $e) {
            Log::error('Une erreur est survenue', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
            return redirect()->route('lieux.search')->with('success', 'Lieu de vente créé.');
    }
}
