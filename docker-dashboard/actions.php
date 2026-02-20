<?php
// Inclure la configuration
require_once 'config.php';

// Vérifier si une action et un ID de conteneur sont passés
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id     = $_GET['id'];

    // Construire l’URL de l’API en fonction de l’action
    switch ($action) {
        case 'start':
            $url = DOCKER_API . "/containers/$id/start";
            $method = 'POST';
            break;

        case 'stop':
            $url = DOCKER_API . "/containers/$id/stop";
            $method = 'POST';
            break;

        case 'restart':
            $url = DOCKER_API . "/containers/$id/restart";
            $method = 'POST';
            break;

        default:
            die("Action non reconnue.");
    }

    // Exécuter la requête cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Erreur cURL : " . curl_error($ch);
    }

    curl_close($ch);

    // Redirection vers le dashboard après l’action
    header("Location: index.php");
    exit;
} else {
    echo "Paramètres manquants (action ou id).";
}
?>