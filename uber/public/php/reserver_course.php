<?php
// Activer le rapport d'erreurs PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Paramètres de connexion à la base de données (remplis avec les informations fournies)
    $host = '127.0.0.1';  // Ou utiliser env('DB_HOST', '127.0.0.1')
    $port = '5432';        // Ou utiliser env('DB_PORT', '5432')
    $database = 's231_uber'; // Ou utiliser env('DB_DATABASE', 's231_uber')
    $username = 's231';     // Ou utiliser env('DB_USERNAME', 's231')
    $password = 'etsmb31'; // Ou utiliser env('DB_PASSWORD', 'etsmb31')
    $charset = 'utf8';
    $search_path = 's_uber'; // Le schéma spécifique

    // Créer la chaîne de connexion DSN
    $dsn = "pgsql:host=$host;port=$port;dbname=$database;options='--search_path=$search_path'";

    // Connexion à la base de données PostgreSQL avec PDO
    $db = new PDO($dsn, $username, $password);

    // Définir le mode de gestion des erreurs de PDO
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données JSON envoyées
    $data = json_decode(file_get_contents('php://input'), true);

    // Extraire les informations spécifiques
    $temps_trajet = $data['temps_trajet'] ?? null;
    $chauffeur_nom = $data['chauffeur']['nom'] ?? null;
    $lieu_depart_rue = $data['lieu_depart_rue'] ?? null;    
    $lieu_depart_ville = $data['lieu_depart_ville'] ?? null;
    $lieu_depart_cp = $data['lieu_depart_cp'] ?? null;
    $lieu_arrivee_rue = $data['lieu_arrivee_rue'] ?? null;
    $lieu_arrivee_ville = $data['lieu_arrivee_ville'] ?? null;
    $lieu_arrivee_cp = $data['lieu_arrivee_cp'] ?? null;
    $prix_reservation = $data['prix_reservation'] ?? null;
    $code_departement_depart = substr($lieu_depart_cp, 0, 2);
    $code_departement_arrivee = substr($lieu_arrivee_cp, 0, 2);
    /*$id_client =  Auth::user()->id_client ?? null;
    dd($idclient);*/

    // Vérifier que les données nécessaires sont présentes
    if ($temps_trajet && $chauffeur_nom && $lieu_depart_rue && $lieu_arrivee_rue) {
        
        // Récupération de l'id du département du départ dans la DB
        $stmrecupdep_depart = $db->prepare("SELECT id_departement FROM departement WHERE CODE_DEPARTEMENT = :code_departement");
        $stmrecupdep_depart->execute([
            ':code_departement' => $code_departement_depart,  
        ]);
        $id_dep_depart = $stmrecupdep_depart->fetch(PDO::FETCH_ASSOC);  // Affiche le résultat
        /*// Récupération de l'id du département de l'arrivée dans la DB
        $stmrecupdep_arrive = $db->prepare("SELECT id_departement FROM departement WHERE CODE_DEPARTEMENT = :code_departement");
        $stmrecupdep_arrive->execute([
            ':code_departement' => $code_departement_arrivee, 
        ]);
        $id_dep_arrivee = $stmrecupdep_arrive->fetch(PDO::FETCH_ASSOC);  // Affiche le résultat*/

        // Préparer l'insertion du depart dans la DB
        $stmdepart = $db->prepare("INSERT INTO adresse (id_departement, rue, ville, cp) VALUES (:id_departement, :rue, :ville, :cp)");
        
        // Exécuter la requête d'insertion
        $stmdepart->execute([
            ':id_departement' => (int)$id_dep_depart,  
            ':rue' => $lieu_depart_rue,
            ':ville' => $lieu_depart_ville,
            ':cp' => $lieu_depart_cp
        ]);


        // Préparer l'insertion de l'arrivée dans la DB
        $stmarrivee = $db->prepare("INSERT INTO adresse (id_departement, rue, ville, cp) VALUES (:id_departement, :rue, :ville, :cp)");
        
        // Exécuter la requête d'insertion
        $stmarrivee->execute([
            ':id_departement' => 8,  
            ':rue' => $lieu_arrivee_rue,
            ':ville' => $lieu_arrivee_ville,
            ':cp' => $lieu_arrivee_cp
        ]);

        // Préparer la réponse à envoyer au client
        $response = [
            'status' => 'success',
            'message' => 'Informations de la course récupérées',
            'details' => [
                'temps_trajet' => $temps_trajet,
                'chauffeur' => $chauffeur_nom,
                'depart' => $lieu_depart_rue,
                'arrivee' => $lieu_arrivee_rue,
                'prix_reservation' => $prix_reservation
            ]
        ];

        // Retourner la réponse en JSON
        header('Content-Type: application/json');
        //echo json_encode($response);
    } else {
        // Si les informations sont manquantes, renvoyer une erreur
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Informations manquantes'
        ]);
    }

} catch (PDOException $e) {
    // Si une erreur de base de données se produit
    error_log('Erreur PDO: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
