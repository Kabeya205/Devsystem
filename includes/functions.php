<?php
require_once __DIR__ . '/../config.php';

/**
 * Fonction générique pour interroger l’API Docker
 *
 * @param string $endpoint  L’endpoint de l’API (ex: "/containers/json")
 * @param string $method    Méthode HTTP (GET, POST, DELETE…)
 * @param array|null $data  Données à envoyer (optionnel)
 * @return mixed            Réponse décodée JSON ou false en cas d’erreur
 */
function dockerApiRequest($endpoint, $method = 'GET', $data = null) {
    $url = DOCKER_API . $endpoint;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, CURL_TIMEOUT);

    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
    }

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        if (DEBUG_MODE) {
            echo "Erreur cURL : " . curl_error($ch);
        }
        curl_close($ch);
        return false;
    }

    curl_close($ch);
    return json_decode($response, true);
}

/**
 * Récupère la liste des conteneurs Docker
 *
 * @return array
 */
function getContainers() {
    return dockerApiRequest("/containers/json?all=true", "GET");
}

/**
 * Récupère les détails d’un conteneur
 *
 * @param string $id
 * @return array
 */
function getContainerDetails($id) {
    return dockerApiRequest("/containers/$id/json", "GET");
}

/**
 * Récupère les logs d’un conteneur
 *
 * @param string $id
 * @return string
 */
function getContainerLogs($id) {
    return dockerApiRequest("/containers/$id/logs?stdout=true&stderr=true", "GET");
}

/**
 * Démarre un conteneur
 *
 * @param string $id
 * @return bool
 */
function startContainer($id) {
    return dockerApiRequest("/containers/$id/start", "POST") !== false;
}

/**
 * Arrête un conteneur
 *
 * @param string $id
 * @return bool
 */
function stopContainer($id) {
    return dockerApiRequest("/containers/$id/stop", "POST") !== false;
}

/**
 * Redémarre un conteneur
 *
 * @param string $id
 * @return bool
 */
function restartContainer($id) {
    return dockerApiRequest("/containers/$id/restart", "POST") !== false;
}
?>