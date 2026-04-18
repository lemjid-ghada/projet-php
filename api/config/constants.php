<?php
/**
 * Constantes Globales
 * Benna Tounsiya - Restaurant API
 */

// URLs
define('BASE_URL', 'http://localhost/api');
define('FRONTEND_URL', 'http://localhost:5173');  // Vite dev server

// Chemins
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('MODELS_PATH', ROOT_PATH . '/models');
define('MIDDLEWARE_PATH', ROOT_PATH . '/middleware');
define('HELPERS_PATH', ROOT_PATH . '/helpers');

// Environnement
define('ENVIRONMENT', getenv('APP_ENV') ?: 'development');
define('DEBUG', ENVIRONMENT === 'development');

// Sécurité
define('JWT_SECRET', 'benna_tounsiya_secret_key_2026');
define('JWT_EXPIRATION', 86400 * 7);  // 7 jours
define('HASH_ALGO', 'bcrypt');
define('PASSWORD_HASH_COST', 10);

// CORS
define('ALLOWED_ORIGINS', [
    'http://localhost:5173',
    'http://localhost:3000',
    'http://localhost',
]);

// Pagination
define('ITEMS_PER_PAGE', 20);
define('MAX_ITEMS_PER_PAGE', 100);

// Fichiers
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);  // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Email (optionnel - à configurer)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
define('SMTP_FROM', 'noreply@bennatounsiya.tn');

// Devise
define('DEFAULT_CURRENCY', 'DT');

// États
define('RESERVATION_STATES', ['pending', 'confirmed', 'cancelled', 'completed']);
define('ORDER_STATES', ['pending', 'preparing', 'ready', 'delivered', 'cancelled']);
define('PAYMENT_METHODS', ['cash', 'card', 'online']);

// Messages
define('MESSAGES', [
    'success' => 'Opération réussie',
    'error' => 'Une erreur s\'est produite',
    'not_found' => 'Ressource introuvable',
    'unauthorized' => 'Non autorisé',
    'forbidden' => 'Accès refusé',
    'invalid_input' => 'Données invalides',
]);

?>
