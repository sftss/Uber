<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Configuration de la connexion à la base de données
    $host = '127.0.0.1';
    $port = '5432';
    $database = 's231_uber';
    $username = 's231';
    $password = 'etsmb31';
    $charset = 'utf8';
    $search_path = 's_uber';

    $dsn = "pgsql:host=$host;port=$port;dbname=$database;options='--search_path=$search_path'";
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données JSON envoyées
    $data = json_decode(file_get_contents('php://input'), true);

    $operation = $_GET['operation'] ?? null;

    if ($operation === 'read_temp') {
        // Lire les données de la table temporaire
        $stmt = $db->query("SELECT * FROM temp_course");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'data' => $rows,
        ]);
    } else {

    
    // Vérifier la validité du tableau des chauffeurs
    $chauffeurtab = $data['chauffeurtab'] ;

    // Créer la table temporaire si elle n'existe pas encore
    $db->exec("
    DROP TABLE IF EXISTS temp_course;
    CREATE TABLE temp_course (
        id_course SERIAL PRIMARY KEY,
        id_chauffeur INT,
        id_velo INT,
        id_lieu_depart INT,
        id_lieu_arrivee INT,
        id_client INT,
        prix_reservation NUMERIC(6,2),
        date_prise_en_charge DATE,
        duree_course TIME,
        heure_arrivee TIME,
        terminee BOOL,
        acceptee BOOL,
        validation_client BOOL,
        validation_chauffeur BOOL,
        pourboire NUMERIC(6,2),
        est_facture BOOL,
        numcourse INT
    )
");


    // Préparer l'insertion
    $stmt = $db->prepare("
        INSERT INTO temp_course 
            (id_chauffeur, id_velo, id_lieu_depart, id_lieu_arrivee, id_client, prix_reservation, date_prise_en_charge, duree_course, heure_arrivee, terminee, numcourse) 
        VALUES 
            (:id_chauffeur, :id_velo, :id_lieu_depart, :id_lieu_arrivee, :id_client, :prix_reservation, :date_prise_en_charge, :duree_course, :heure_arrivee, :terminee, :numcourse)
    ");

    // Parcourir chaque chauffeur et créer une course
    foreach ($chauffeurtab as $chauffeur) {
        $id_chauffeur = $chauffeur['id_chauffeur'] ?? null;

        if (!$id_chauffeur) {
            // Si l'id du chauffeur est manquant, passer au suivant
            continue;
        }

        $stmt->execute([
            ':id_chauffeur' => $id_chauffeur,
            ':id_velo' => $data['id_velo'] ?? null,
            ':id_lieu_depart' => $data['id_lieu_depart'] ?? null,
            ':id_lieu_arrivee' => $data['id_lieu_arrivee'] ?? null,
            ':id_client' => $data['id_client'] ?? 1, // Par défaut, client ID = 1
            ':prix_reservation' => $data['prix_reservation'] ?? null,
            ':date_prise_en_charge' => $data['date_prise_en_charge'] ?? null,
            ':duree_course' => $data['duree_course'] ?? null,
            ':heure_arrivee' => null,
            ':terminee' => 'false',
            ':numcourse' => $data['id_course'] ?? null,

        ]);
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Courses créées avec succès pour tous les chauffeurs.',
    ]);
}

} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    error_log('Erreur PDO: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur serveur: ' . $e->getMessage(),
    ]);
} catch (Exception $e) {
    // Gestion des autres erreurs
    error_log('Erreur: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
    ]);
}
