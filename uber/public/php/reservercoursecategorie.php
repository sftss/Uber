<?php
// Activer le rapport d'erreurs PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Paramètres de connexion à la base de données
    $host = '127.0.0.1';  
    $port = '5432';        
    $database = 's231_uber'; 
    $username = 's231';     
    $password = 'etsmb31'; 
    $charset = 'utf8';
    $search_path = 's_uber'; 

    // Créer la chaîne de connexion DSN
    $dsn = "pgsql:host=$host;port=$port;dbname=$database;options='--search_path=$search_path'";

    // Connexion à la base de données PostgreSQL avec PDO
    $db = new PDO($dsn, $username, $password);

    // Définir le mode de gestion des erreurs de PDO
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données JSON envoyées
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifiez si la conversion JSON a échoué
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Erreur dans les données JSON envoyées.',
            'details' => json_last_error_msg()
        ]);
        exit;
    }

    // Extraire les informations spécifiques
    $categorie = $data['categorie'] ?? null;
    $lieu_depart_rue = $data['lieu_depart_rue'] ?? null;    
    $lieu_depart_ville = $data['lieu_depart_ville'] ?? null;
    $lieu_depart_cp = $data['lieu_depart_cp'] ?? null;
    $lieu_arrivee_rue = $data['lieu_arrivee_rue'] ?? null;
    $lieu_arrivee_ville = $data['lieu_arrivee_ville'] ?? null;
    $lieu_arrivee_cp = $data['lieu_arrivee_cp'] ?? null;
    $prix_reservation = $data['prix_reservation'] ?? null;
    $tempscourse = $data['tempscourse'] ?? null;
    $date_depart = $data['date_trajet'] ?? null;
    $id_client = $data['id_client'] ?? null;

    // Calculer les codes des départements et la durée de la course
    $code_departement_depart = substr($lieu_depart_cp, 0, 2);
    $code_departement_arrivee = substr($lieu_arrivee_cp, 0, 2);

    $heurescourse = floor($tempscourse / 3600);
    $minutescourse = floor(($tempscourse % 3600) / 60);
    $secondescourse = $tempscourse % 60;

    // Vérifier que les données nécessaires sont présentes
    if ($categorie && $lieu_depart_rue && $lieu_arrivee_rue) {

        // Récupérer l'id du département de départ
        $stmrecupdep_depart = $db->prepare("SELECT id_departement FROM departement WHERE CODE_DEPARTEMENT = :code_departement");
        $stmrecupdep_depart->execute([ ':code_departement' => $code_departement_depart ]);
        $id_dep_depart = $stmrecupdep_depart->fetch(PDO::FETCH_ASSOC);
        $id_dep_depart_val = isset($id_dep_depart['id_departement']) ? $id_dep_depart['id_departement'] : null;

        // Récupérer l'id du département d'arrivée
        $stmrecupdep_arrivee = $db->prepare("SELECT id_departement FROM departement WHERE CODE_DEPARTEMENT = :code_departement");
        $stmrecupdep_arrivee->execute([ ':code_departement' => $code_departement_arrivee ]);
        $id_dep_arrivee = $stmrecupdep_arrivee->fetch(PDO::FETCH_ASSOC);
        $id_dep_arrivee_val = isset($id_dep_arrivee['id_departement']) ? $id_dep_arrivee['id_departement'] : null;

        // Si les départements n'ont pas été trouvés, afficher une erreur
        if (!$id_dep_depart_val || !$id_dep_arrivee_val) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Code département non trouvé'
            ]);
            exit;
        }

        // Insérer les adresses de départ et d'arrivée
        $stmdepart = $db->prepare("INSERT INTO adresse (id_departement, rue, ville, cp) VALUES (:id_departement, :rue, :ville, :cp)");
        $stmdepart->execute([ ':id_departement' => intval($id_dep_depart_val), ':rue' => $lieu_depart_rue, ':ville' => $lieu_depart_ville, ':cp' => $lieu_depart_cp ]);
        $stmarrivee = $db->prepare("INSERT INTO adresse (id_departement, rue, ville, cp) VALUES (:id_departement, :rue, :ville, :cp)");
        $stmarrivee->execute([ ':id_departement' => intval($id_dep_arrivee_val), ':rue' => $lieu_arrivee_rue, ':ville' => $lieu_arrivee_ville, ':cp' => $lieu_arrivee_cp ]);

        // Récupérer l'ID des adresses de départ et d'arrivée
        $stmid_adresse_depart = $db->prepare("SELECT id_adresse FROM adresse WHERE RUE = :rue AND VILLE = :ville AND CP = :cp");
        $stmid_adresse_depart->execute([ ':rue' => $lieu_depart_rue, ':ville' => $lieu_depart_ville, ':cp' => $lieu_depart_cp ]);
        $id_adresse_depart = $stmid_adresse_depart->fetch(PDO::FETCH_ASSOC)['id_adresse'];

        $stmid_adresse_arrivee = $db->prepare("SELECT id_adresse FROM adresse WHERE RUE = :rue AND VILLE = :ville AND CP = :cp");
        $stmid_adresse_arrivee->execute([ ':rue' => $lieu_arrivee_rue, ':ville' => $lieu_arrivee_ville, ':cp' => $lieu_arrivee_cp ]);
        $id_adresse_arrivee = $stmid_adresse_arrivee->fetch(PDO::FETCH_ASSOC)['id_adresse'];

        // Récupérer l'id du chauffeur en fonction de la catégorie de véhicule
        $stmid_chauffeur = $db->prepare("SELECT CH.id_chauffeur FROM chauffeur CH JOIN vehicule V ON V.id_chauffeur=CH.id_chauffeur JOIN categorie_vehicule CAT ON CAT.id_categorie_vehicule=V.id_categorie_vehicule WHERE lib_categorie_vehicule = :categorie LIMIT 1");
        $stmid_chauffeur->execute([ ':categorie' => $categorie ]);
        $id_chauffeur = $stmid_chauffeur->fetch(PDO::FETCH_ASSOC)['id_chauffeur'];

        $duree_course = $heurescourse . ":" . $minutescourse . ":" . $secondescourse;

        $terminée = 'false';

        $stm_insert_course = $db->prepare("INSERT INTO course 
        (ID_CHAUFFEUR, ID_VELO, ID_LIEU_DEPART, ID_LIEU_ARRIVEE, ID_CLIENT, PRIX_RESERVATION, DATE_PRISE_EN_CHARGE, DUREE_COURSE, heure_arrivee, TERMINEE) 
        VALUES 
        (:id_chauffeur, :id_velo, :id_lieu_depart, :id_lieu_arrivee, :id_client, :prix_reservation, :date_prise_en_charge, :duree_course, :heure_arrivee, :terminee)");
        $stm_insert_course->execute([
        ':id_chauffeur' => $id_chauffeur,
        ':id_velo' => null, // ou un id vélo valide si applicable
        ':id_lieu_depart' => $id_adresse_depart,
        ':id_lieu_arrivee' => $id_adresse_arrivee,
        ':id_client' => $id_client, // Assurez-vous que l'ID du client est valide
        ':prix_reservation' => $prix_reservation,
        ':date_prise_en_charge' => substr($date_depart, 0, 10),
        ':duree_course' => $duree_course,
        ':heure_arrivee' => null, // ou l'heure d'arrivée valide
        ':terminee' => $terminée, // si vous souhaitez marquer la course comme non terminée
        ]);

        // Préparer la réponse à envoyer au client
        $response = [
            'status' => 'success',
            'message' => 'Course créée avec succès.',
            'details' => [
                'chauffeur' => $id_chauffeur,
                'depart' => $id_adresse_depart,
                'arrivee' => $id_adresse_arrivee,
                'prix_reservation' => $prix_reservation,
                'date_trajet' => substr($date_depart, 0, 10),
                'duree_course' => $duree_course
            ]
        ];

        // Retourner la réponse en JSON
        header('Content-Type: application/json');
        echo json_encode($response);

    } else {
        // Si les informations sont manquantes, renvoyer une erreur
        echo json_encode([
            'status' => 'error',
            'message' => 'Informations manquantes. Veuillez fournir toutes les données nécessaires.'
        ]);
    }

} catch (PDOException $e) {
    // Si une erreur de base de données se produit, on retourne l'erreur en JSON
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données',
        'details' => $e->getMessage()
    ]);
}
