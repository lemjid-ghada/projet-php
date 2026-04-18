<?php
/**
 * Helper pour les Réponses API
 * Standardise toutes les réponses
 */

class Response {
    /**
     * Réponse de succès
     */
    public static function success($data = null, $message = 'Success', $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }

    /**
     * Réponse d'erreur
     */
    public static function error($message = 'Error', $statusCode = 400, $data = null) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        
        echo json_encode([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }

    /**
     * Réponse 404 Not Found
     */
    public static function notFound($message = 'Ressource introuvable') {
        self::error($message, 404);
    }

    /**
     * Réponse 401 Unauthorized
     */
    public static function unauthorized($message = 'Non authentifié') {
        self::error($message, 401);
    }

    /**
     * Réponse 403 Forbidden
     */
    public static function forbidden($message = 'Accès refusé') {
        self::error($message, 403);
    }

    /**
     * Réponse 400 Bad Request
     */
    public static function badRequest($message = 'Requête invalide', $errors = null) {
        self::error($message, 400, $errors);
    }

    /**
     * Réponse 500 Internal Server Error
     */
    public static function internalError($message = 'Erreur interne du serveur') {
        if (DEBUG) {
            // En développement, afficher les détails
            self::error($message, 500);
        } else {
            // En production, ne pas révéler les détails
            self::error('Erreur interne du serveur', 500);
        }
    }

    /**
     * Réponse de pagination
     */
    public static function paginated($items, $total, $page, $perPage, $message = 'Success') {
        http_response_code(200);
        header('Content-Type: application/json; charset=utf-8');
        
        $totalPages = ceil($total / $perPage);
        
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $items,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => $totalPages
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }
}

?>
