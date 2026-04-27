<?php
// Charger les configurations AVANT les headers
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/Response.php';
require_once __DIR__ . '/helpers/Validator.php';
require_once __DIR__ . '/helpers/Auth.php';

// Maintenant les headers CORS avec les bonnes constantes
header('Access-Control-Allow-Origin: ' . FRONTEND_URL);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

// Gérer les requêtes PREFLIGHT (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

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
            } elseif ($method === 'POST' && $action === 'verify') {
                $authController->verifyToken();
            } elseif ($method === 'POST' && $action === 'refresh') {
                $authController->refreshToken();
            } elseif ($method === 'POST' && $action === 'change-password') {
                $authController->changePassword();
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

        case 'users':
            require_once CONTROLLERS_PATH . '/UserController.php';
            $userController = new UserController();
            
            if ($method === 'GET' && !$id && $action === 'statistics') {
                $userController->getStatistics();
            } elseif ($method === 'GET' && !$id && $action === 'clients') {
                $userController->getAllClients();
            } elseif ($method === 'GET' && !$id && $action === 'admins') {
                $userController->getAllAdmins();
            } elseif ($method === 'GET' && !$id && $action === 'search') {
                $userController->search();
            } elseif ($method === 'GET' && !$id) {
                $userController->getAll();
            } elseif ($method === 'GET' && $id) {
                $userController->getById($id);
            } elseif ($method === 'POST' && !$action) {
                $userController->create();
            } elseif ($method === 'PUT' && $id) {
                $userController->update($id);
            } elseif ($method === 'POST' && $id && $action === 'role') {
                $userController->changeRole($id);
            } elseif ($method === 'POST' && $id && $action === 'deactivate') {
                $userController->deactivate($id);
            } elseif ($method === 'POST' && $id && $action === 'activate') {
                $userController->activate($id);
            } elseif ($method === 'DELETE' && $id) {
                $userController->delete($id);
            } else {
                Response::notFound('Endpoint utilisateur non trouvé');
            }
            break;

        case 'contact':
            require_once CONTROLLERS_PATH . '/ContactController.php';
            $contactController = new ContactController();
            
            if ($method === 'GET' && !$id && $action === 'statistics') {
                $contactController->getStatistics();
            } elseif ($method === 'GET' && !$id && $action === 'new') {
                $contactController->getNewMessages();
            } elseif ($method === 'GET' && !$id && $action === 'search') {
                $contactController->search();
            } elseif ($method === 'GET' && !$id) {
                $contactController->getAll();
            } elseif ($method === 'GET' && $id) {
                $contactController->getById($id);
            } elseif ($method === 'POST' && !$id) {
                $contactController->sendMessage();
            } elseif ($method === 'POST' && $id && $action === 'reply') {
                $contactController->reply($id);
            } elseif ($method === 'POST' && $id && $action === 'read') {
                $contactController->markAsRead($id);
            } elseif ($method === 'DELETE' && $id) {
                $contactController->delete($id);
            } else {
                Response::notFound('Endpoint contact non trouvé');
            }
            break;

        default:
Response::notFound("Endpoint [$resource] non trouvé");            break;
    }

} catch (Exception $e) {
    if (DEBUG) {
        Response::internalError($e->getMessage());
    } else {
        Response::internalError('Une erreur s\'est produite');
    }
}
?>
