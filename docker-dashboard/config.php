<?php
/**
 * Configuration du dashboard Docker en PHP
 */

// Adresse de l’API Docker
// Par défaut, Docker écoute sur le socket Unix : /var/run/docker.sock
// Si tu as activé l’API TCP (dockerd -H tcp://127.0.0.1:2375), utilise l’URL ci-dessous :
define('DOCKER_API', 'http://localhost:2375');

// Timeout pour les requêtes cURL (en secondes)
define('CURL_TIMEOUT', 10);

// Optionnel : activer le mode debug pour afficher les erreurs
define('DEBUG_MODE', true);
?>