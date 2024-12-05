<?php
use App\Http\Controllers\Controller;
use App\Models\Adresse;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// Vérifier que les données POST sont présentes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données JSON envoyées
    $data = json_decode(file_get_contents('php://input'), true);

    // Extraire les informations spécifiques
    $temps_trajet = $data['temps_trajet'] ?? null;
    $chauffeur_nom = $data['chauffeur']['nom'] ?? null;
    $lieu_depart_rue = $data['lieu_depart_rue'] ?? null;
    $lieu_depart_ville = $data['lieu_depart_ville'] ?? null;
    $lieu_depart_cp = $data['lieu_depart_cp'] ?? null;
    $lieu_arrivee_rue = $data['lieu_arrivee'] ?? null;
    $lieu_arrivee_ville = $data['lieu_arrivee'] ?? null;
    $lieu_arrivee_cp = $data['lieu_arrivee'] ?? null;
    $prix_reservation = $data['prix_reservation'] ?? null;

   /*$adresse = Adresse::create([
        'id_departement' => 1,
        'rue' => $request->lieu_depart->rue,
        'ville' => $request->lieu_depart->ville,
        'cp' => $request->lieu_depart->code_postal,  // Hashage du mot de passe
    ]);

    // Création d'un nouveau course
    $course = Course::create([
        'id_chauffeur' => $request->prenom_cp,
        'id_velo' => $request->nom_cp,
        'id_lieu_depart' => $request->mail_client,
        'id_lieu_arrivee' => Hash::make($request->mdp_client),  // Hashage du mot de passe
        'id_client' => 1,  // Vous pouvez ajuster cette valeur en fonction de votre logique
        'prix_reservation' => $request->tel_client,
        'date_prise_en_charge' => $request->num_siret,
        'duree_course' => $request->sexe_cp,
        'temps_arrivee' => null,  // true si la case est cochée, false sinon
    ]);*/


    // Vérifier que toutes les informations sont présentes
    if ($temps_trajet && $chauffeur_nom && $lieu_depart_rue && $lieu_arrivee) {
        // Vous pouvez maintenant enregistrer ces informations en base de données
        // Ou les utiliser comme vous le souhaitez
        
        // Exemple de réponse JSON
        $response = [
            'status' => 'success',
            'message' => 'Informations de la course récupérées',
            'details' => [
                'temps_trajet' => $temps_trajet,
                'chauffeur' => $chauffeur_nom,
                'depart' => $lieu_depart_rue,
                'arrivee' => $lieu_arrivee,
                'prix_reservation' => $prix_reservation
            ]
        ];

        // Envoyer la réponse
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Données manquantes
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Informations manquantes'
        ]);
    }
} else {
    // Méthode de requête incorrecte
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Méthode non autorisée'
    ]);
}