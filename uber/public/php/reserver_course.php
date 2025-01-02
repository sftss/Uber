<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    $host = '127.0.0.1';  
    $port = '5432';    
    $database = 's231_uber'; 
    $username = 's231';     
    $password = 'etsmb31'; 
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
    $chauffeur_nom = $data['chauffeur_nom'] ?? null;
    $chauffeur_prenom = $data['chauffeur_prenom'] ?? null;
    $lieu_depart_rue = $data['lieu_depart_rue'] ?? null;    
    $lieu_depart_ville = $data['lieu_depart_ville'] ?? null;
    $lieu_depart_cp = $data['lieu_depart_cp'] ?? null;
    $lieu_arrivee_rue = $data['lieu_arrivee_rue'] ?? null;
    $lieu_arrivee_ville = $data['lieu_arrivee_ville'] ?? null;
    $lieu_arrivee_cp = $data['lieu_arrivee_cp'] ?? null;
    $prix_reservation = $data['prix_reservation'] ?? null;
    $tempscourse = $data['tempscourse'] ?? null;
    $date_depart = $data['date_trajet'] ?? null;
    $operation = $data['operation'] ?? null;
    $chauffeurstableau=$data['chauffeurtab'] ?? null;

    $code_departement_depart = substr($lieu_depart_cp, 0, 2);
    $code_departement_arrivee = substr($lieu_arrivee_cp, 0, 2);

    $heurescourse = floor($tempscourse / 3600);
    $minutescourse = floor(($tempscourse % 3600) / 60);
    $secondescourse = $tempscourse % 60;

    /*$id_client =  auth()->user()->id_client ?? null;
    if($id_client==null){
        $id_client=1;
    }*/




    // Vérifier que les données nécessaires sont présentes
        
        // Récupération de l'id du département du départ et d'arrivée dans la DB
        $stmrecupdep_depart = $db->prepare("SELECT id_departement FROM departement WHERE CODE_DEPARTEMENT = :code_departement");
        $stmrecupdep_depart->execute([
            ':code_departement' => $code_departement_depart,  
        ]);
        $id_dep_depart = $stmrecupdep_depart->fetch(PDO::FETCH_ASSOC);
        $id_dep_depart_val = $id_dep_depart['id_departement'];  

        $stmrecupdep_arrivee = $db->prepare("SELECT id_departement FROM departement WHERE CODE_DEPARTEMENT = :code_departement");
        $stmrecupdep_arrivee->execute([
            ':code_departement' => $code_departement_arrivee,  
        ]);
        $id_dep_arrivee = $stmrecupdep_arrivee->fetch(PDO::FETCH_ASSOC);
        $id_dep_arrivee_val = $id_dep_arrivee['id_departement'];



        // Préparer l'insertion du depart dans la DB
        $stmdepart = $db->prepare("INSERT INTO adresse (id_departement, rue, ville, cp) VALUES (:id_departement, :rue, :ville, :cp)");
        
        // Exécuter la requête d'insertion
        $stmdepart->execute([
            ':id_departement' => intval($id_dep_depart_val),  
            ':rue' => $lieu_depart_rue,
            ':ville' => $lieu_depart_ville,
            ':cp' => $lieu_depart_cp
        ]);


        // Préparer l'insertion de l'arrivée dans la DB
        $stmarrivee = $db->prepare("INSERT INTO adresse (id_departement, rue, ville, cp) VALUES (:id_departement, :rue, :ville, :cp)");
        
        // Exécuter la requête d'insertion
        $stmarrivee->execute([
            ':id_departement' => intval($id_dep_arrivee_val),  
            ':rue' => $lieu_arrivee_rue,
            ':ville' => $lieu_arrivee_ville,
            ':cp' => $lieu_arrivee_cp
        ]);


        //Recup id adresse de depart
        $stmid_adresse_depart = $db->prepare("SELECT id_adresse FROM adresse WHERE RUE = :rue and VILLE = :ville and CP = :cp");
        $stmid_adresse_depart->execute([
            ':rue' => $lieu_depart_rue,
            ':ville' => $lieu_depart_ville,
            ':cp' => $lieu_depart_cp
        ]);
        $id_adresse_depart = $stmid_adresse_depart->fetch(PDO::FETCH_ASSOC);
        $id_adresse_depart = $id_adresse_depart['id_adresse'];  

        
        //Recup id adresse d'arrivée
        $stmid_adresse_arrivee = $db->prepare("SELECT id_adresse FROM adresse WHERE RUE = :rue and VILLE = :ville and CP = :cp");
        $stmid_adresse_arrivee->execute([
            ':rue' => $lieu_arrivee_rue,
            ':ville' => $lieu_arrivee_ville,
            ':cp' => $lieu_arrivee_cp
        ]);
        $id_adresse_arrivee = $stmid_adresse_arrivee->fetch(PDO::FETCH_ASSOC);
        $id_adresse_arrivee = $id_adresse_arrivee['id_adresse']; 

        


        //Recup id chauffeur avec son nom et prenom
       /* $stmid_chauffeur = $db->prepare("SELECT id_chauffeur FROM chauffeur WHERE nom_chauffeur = :nom and prenom_chauffeur = :prenom ");
        $stmid_chauffeur->execute([
            ':nom' => $chauffeur_nom,
            ':prenom' => $chauffeur_prenom,
        ]);
        $id_chauffeur = $stmid_chauffeur->fetch(PDO::FETCH_ASSOC);
        $id_chauffeur = $id_chauffeur['id_chauffeur']; */
        $duree_course = $heurescourse . ":" . $minutescourse . ":" . $secondescourse;

        $terminée = 'false';
        if{$operation=='insert'}{
        //Insertion de la course ENFIN WALLAH 
        $stm_insert_course = $db->prepare("INSERT INTO course 
        (ID_CHAUFFEUR, ID_VELO, ID_LIEU_DEPART, ID_LIEU_ARRIVEE, ID_CLIENT, PRIX_RESERVATION, DATE_PRISE_EN_CHARGE, DUREE_COURSE, heure_arrivee, TERMINEE) 
        VALUES 
        (:id_chauffeur, :id_velo, :id_lieu_depart, :id_lieu_arrivee, :id_client, :prix_reservation, :date_prise_en_charge, :duree_course, :heure_arrivee, :terminee)");
        $stm_insert_course->execute([
        ':id_chauffeur' => null,
        ':id_velo' => null, // ou un id vélo valide si applicable
        ':id_lieu_depart' => $id_adresse_depart,
        ':id_lieu_arrivee' => $id_adresse_arrivee,
        ':id_client' => 1, // Assurez-vous que l'ID du client est valide
        ':prix_reservation' => $prix_reservation,
        ':date_prise_en_charge' => substr($date_depart, 0, 10),
        ':duree_course' => $duree_course,
        ':heure_arrivee' => null, // ou l'heure d'arrivée valide
        ':terminee' => $terminée, // si vous souhaitez marquer la course comme non terminée
        ]);
    
        // Préparer la réponse à envoyer au client
        $response = [
            'status' => 'success',
            'message' => 'Informations de la course récupérées',
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
        echo json_encode( $response );
}
    else if {$operation=='table'}{
                // Créer une table temporaire

                   $db->exec("CREATE TEMPORARY TABLE temp_course (
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
                    terminee BOOL
        )");
            

        
        // Préparer l'insertion des données dans la table temporaire
        $stmt = $db->prepare("INSERT INTO temp_course 
            (id_chauffeur, id_velo, id_lieu_depart, id_lieu_arrivee, id_client, prix_reservation, date_prise_en_charge, duree_course, heure_arrivee, terminee) 
            VALUES 
            (:id_chauffeur, :id_velo, :id_lieu_depart, :id_lieu_arrivee, :id_client, :prix_reservation, :date_prise_en_charge, :duree_course, :heure_arrivee, :terminee)");

        // Exécuter l'insertion
        $stmt->execute([
        ':id_chauffeur' => null,
        ':id_velo' => null, 
        ':id_lieu_depart' => $id_adresse_depart,
        ':id_lieu_arrivee' => $id_adresse_arrivee,
        ':id_client' => 1, 
        ':prix_reservation' => $prix_reservation,
        ':date_prise_en_charge' => substr($date_depart, 0, 10),
        ':duree_course' => $duree_course,
        ':heure_arrivee' => null, 
        ':terminee' => $terminée,
        ]);
        
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Table temporaire créée et données insérées avec succès.'
                ]);
            }
            
        else if ($operation=='delete'){
            $db->exec("DROP TABLE IF EXISTS temp_course");
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
