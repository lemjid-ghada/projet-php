<?php
/**
 * Déconnexion
 * POST /api/auth/logout.php
 */

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $userName = $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
    
    // Détruire la session
    $_SESSION = array();
    
    // Supprimer le cookie de session
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Détruire la session
    session_destroy();
    
    echo json_encode([
        "success" => true,
        "message" => "Déconnexion réussie. À bientôt $userName !"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Aucune session active"
    ]);
}
?>