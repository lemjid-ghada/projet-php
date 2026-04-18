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
