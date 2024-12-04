<?php
// Vérifier que les données POST sont présentes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données JSON envoyées
    $data = json_decode(file_get_contents('php://input'), true);

    // Extraire les informations spécifiques
    $temps_trajet = $data['temps_trajet'] ?? null;
    $chauffeur_nom = $data['chauffeur']['nom'] ?? null;
    $lieu_depart = $data['lieu_depart'] ?? null;
    $lieu_arrivee = $data['lieu_arrivee'] ?? null;

    // Vérifier que toutes les informations sont présentes
    if ($temps_trajet && $chauffeur_nom && $lieu_depart && $lieu_arrivee) {
        // Vous pouvez maintenant enregistrer ces informations en base de données
        // Ou les utiliser comme vous le souhaitez
        
        // Exemple de réponse JSON
        $response = [
            'status' => 'success',
            'message' => 'Informations de la course récupérées',
            'details' => [
                'temps_trajet' => $temps_trajet,
                'chauffeur' => $chauffeur_nom,
                'depart' => $lieu_depart,
                'arrivee' => $lieu_arrivee
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