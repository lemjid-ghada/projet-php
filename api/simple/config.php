<?php
/**
 * CONFIGURATION - Backend PHP Simple
 * Base de données et constantes globales
 */

// ==================== CONFIG ====================

// Base de données
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'benna_tounsiya';

// URLs & Sécurité
$app_url = 'http://localhost';
$frontend_url = 'http://localhost:5173';
$jwt_secret = 'benna_tounsiya_secret_key_2026';
$jwt_expiration = 604800; // 7 jours

// ==================== CONNEXION BD ====================

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Erreur de connexion BD']));
}

$conn->set_charset('utf8mb4');

// ==================== HEADERS ====================

header('Access-Control-Allow-Origin: ' . $frontend_url);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

?>
