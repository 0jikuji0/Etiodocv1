<?php
session_start();

// Définir le type de contenu comme JSON
header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
$logged_in = isset($_SESSION['pseudo']) && isset($_SESSION['id']);

// Vérifier si la session n'a pas expiré
if ($logged_in && isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > 1800) { // 30 minutes
        $logged_in = false;
        session_unset();
        session_destroy();
    } else {
        // Mettre à jour le timestamp de la dernière activité
        $_SESSION['last_activity'] = time();
    }
}

// Retourner le statut de la session
echo json_encode([
    'logged_in' => $logged_in,
    'timestamp' => time(),
    'user' => $logged_in ? $_SESSION['pseudo'] : null
]);
?>