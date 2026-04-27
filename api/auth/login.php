<?php
/**
 * Connexion Client & Admin
 * POST /api/auth/login.php
 */

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

// Lire les données JSON
$data = json_decode(file_get_contents("php://input"), true);

// Vérifier les données
if ($data === null || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode([
        "success" => false, 
        "message" => "Email et mot de passe requis"
    ]);
    exit();
}

// Nettoyer les entrées
$email = trim(strtolower($db->escape($data['email'])));
$password = $data['password'];

// Validation de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Email invalide"]);
    exit();
}

// Chercher l'utilisateur dans la base
$sql = "SELECT id, nom, prenom, email, telephone, password, role, address, is_active 
        FROM utilisateurs 
        WHERE email = ?";

$stmt = $db->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si l'utilisateur existe
if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false, 
        "message" => "Email ou mot de passe incorrect"
    ]);
    $stmt->close();
    exit();
}

$user = $result->fetch_assoc();

// Vérifier si le compte est actif (si la colonne existe)
if (isset($user['is_active']) && $user['is_active'] == 0) {
    echo json_encode([
        "success" => false, 
        "message" => "Votre compte est désactivé. Contactez l'administrateur."
    ]);
    $stmt->close();
    exit();
}

// Vérifier le mot de passe
if (!password_verify($password, $user['password'])) {
    echo json_encode([
        "success" => false, 
        "message" => "Email ou mot de passe incorrect"
    ]);
    $stmt->close();
    exit();
}

// Créer la session
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_nom'] = $user['nom'];
$_SESSION['user_prenom'] = $user['prenom'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];
$_SESSION['user_address'] = $user['address'];
$_SESSION['login_time'] = time();

// Message de bienvenue personnalisé
$welcomeMessage = ($user['role'] === 'admin') 
    ? "Bienvenue Administrateur {$user['prenom']} !" 
    : "Bienvenue {$user['prenom']} {$user['nom']} !";

echo json_encode([
    "success" => true,
    "message" => $welcomeMessage,
    "user" => [
        "id" => $user['id'],
        "nom" => $user['nom'],
        "prenom" => $user['prenom'],
        "email" => $user['email'],
        "telephone" => $user['telephone'],
        "role" => $user['role'],
        "address" => $user['address']
    ]
]);

$stmt->close();
?>