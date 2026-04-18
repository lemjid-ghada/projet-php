<?php
/**
 * Index Principal - Point d'entrée API
 * Benna Tounsiya - Restaurant API
 */

// Headers CORS
header('Access-Control-Allow-Origin: ' . FRONTEND_URL);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Charger les configurations
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/Response.php';
require_once __DIR__ . '/helpers/Validator.php';
require_once __DIR__ . '/helpers/Auth.php';

// Obtenir la route
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api', '', $path);
$path = trim($path, '/');

// Parser les segments de la route
$segments = explode('/', $path);
$resource = $segments[0] ?? '';
$action = $segments[1] ?? '';
$id = $segments[2] ?? '';

// Logging (optionnel)
if (DEBUG) {
    error_log("API Request: $method $path");
}

// Router simple
try {
    switch ($resource) {
        case 'health':
            Response::success(['status' => 'API is running'], 'API Health Check');
            break;

        case 'auth':
            require_once CONTROLLERS_PATH . '/AuthController.php';
            $authController = new AuthController();
            
            if ($method === 'POST' && $action === 'register') {
                $authController->register();
            } elseif ($method === 'POST' && $action === 'login') {
                $authController->login();
            } elseif ($method === 'POST' && $action === 'logout') {
                $authController->logout();
            } else {
                Response::notFound('Endpoint d\'authentification non trouvé');
            }
            break;

        case 'dishes':
            require_once CONTROLLERS_PATH . '/DishController.php';
            $dishController = new DishController();
            
            if ($method === 'GET' && !$id) {
                $dishController->getAll();
            } elseif ($method === 'GET' && $id) {
                $dishController->getById($id);
            } else {
                Response::notFound('Endpoint plat non trouvé');
            }
            break;

        case 'categories':
            require_once CONTROLLERS_PATH . '/DishController.php';
            $dishController = new DishController();
            
            if ($method === 'GET') {
                $dishController->getCategories();
            } else {
                Response::notFound('Endpoint catégorie non trouvé');
            }
            break;

        case 'reservations':
            require_once CONTROLLERS_PATH . '/ReservationController.php';
            $reservationController = new ReservationController();
            
            if ($method === 'GET' && !$id) {
                $reservationController->getAll();
            } elseif ($method === 'GET' && $id) {
                $reservationController->getById($id);
            } elseif ($method === 'POST') {
                $reservationController->create();
            } elseif ($method === 'PUT' && $id) {
                $reservationController->update($id);
            } elseif ($method === 'DELETE' && $id) {
                $reservationController->delete($id);
            } else {
                Response::notFound('Endpoint réservation non trouvé');
            }
            break;

        case 'orders':
            require_once CONTROLLERS_PATH . '/OrderController.php';
            $orderController = new OrderController();
            
            if ($method === 'GET' && !$id) {
                $orderController->getAll();
            } elseif ($method === 'GET' && $id) {
                $orderController->getById($id);
            } elseif ($method === 'POST') {
                $orderController->create();
            } elseif ($method === 'PUT' && $id) {
                $orderController->update($id);
            } elseif ($method === 'DELETE' && $id) {
                $orderController->delete($id);
            } else {
                Response::notFound('Endpoint commande non trouvé');
            }
            break;

        case 'contact':
            require_once CONTROLLERS_PATH . '/ContactController.php';
            $contactController = new ContactController();
            
            if ($method === 'POST') {
                $contactController->sendMessage();
            } else {
                Response::notFound('Endpoint contact non trouvé');
            }
            break;

        default:
            Response::notFound('Endpoint [{$resource}] non trouvé');
            break;
    }

} catch (Exception $e) {
    if (DEBUG) {
        Response::internalError($e->getMessage());
    } else {
        Response::internalError('Une erreur s\'est produite');
    }
}
?>
