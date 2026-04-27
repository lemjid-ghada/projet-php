<?php
/**
 * Middleware d'Authentification
 * Vérifie les tokens JWT
 */

class AuthMiddleware {
    /**
     * Vérifier le token JWT
     */
    public static function verify() {
        $token = Auth::getTokenFromHeader();
        
        if (!$token) {
            Response::unauthorized('Token manquant');
        }

        $payload = Auth::verifyJWT($token);
        
        if (!$payload) {
            Response::unauthorized('Token invalide ou expiré');
        }

        return $payload;
    }

    /**
     * Vérifier si l'utilisateur est authentifié
     */
    public static function isAuthenticated() {
        $token = Auth::getTokenFromHeader();
        
        if (!$token) {
            return false;
        }

        $payload = Auth::verifyJWT($token);
        return $payload !== null;
    }

    /**
     * Obtenir l'utilisateur actuel
     */
    public static function getCurrentUser() {
        $payload = self::verify();
        return $payload;
    }

    /**
     * Vérifier le rôle
     */
    public static function checkRole($allowedRoles) {
        $user = self::getCurrentUser();
        
        if (!in_array($user['role'] ?? '', $allowedRoles)) {
            Response::forbidden('Vous n\'avez pas les permissions nécessaires');
        }
    }
}
?>
<?php
/**
 * Middleware d'authentification
 * Inclure ce fichier dans les routes protégées
 */

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Vérifier si l'utilisateur est authentifié
 */
function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

/**
 * Vérifier si l'utilisateur est un administrateur
 */
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Vérifier si l'utilisateur est un client
 */
function isClient() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client';
}

/**
 * Exiger que l'utilisateur soit authentifié
 */
function requireAuth() {
    if (!isAuthenticated()) {
        http_response_code(401);
        echo json_encode([
            "success" => false, 
            "message" => "Accès non autorisé. Veuillez vous connecter."
        ]);
        exit();
    }
}

/**
 * Exiger que l'utilisateur soit administrateur
 */
function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode([
            "success" => false, 
            "message" => "Accès refusé. Zone réservée aux administrateurs."
        ]);
        exit();
    }
}

/**
 * Exiger que l'utilisateur soit client
 */
function requireClient() {
    requireAuth();
    if (!isClient()) {
        http_response_code(403);
        echo json_encode([
            "success" => false, 
            "message" => "Accès refusé. Zone réservée aux clients."
        ]);
        exit();
    }
}

/**
 * Récupérer l'utilisateur connecté
 */
function getCurrentUser() {
    if (!isAuthenticated()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'nom' => $_SESSION['user_nom'],
        'prenom' => $_SESSION['user_prenom'],
        'email' => $_SESSION['user_email'],
        'role' => $_SESSION['user_role'],
        'address' => $_SESSION['user_address'] ?? null
    ];
}

/**
 * Rafraîchir la session (prolonger l'expiration)
 */
function refreshSession() {
    if (isAuthenticated()) {
        $_SESSION['last_activity'] = time();
    }
}
?>