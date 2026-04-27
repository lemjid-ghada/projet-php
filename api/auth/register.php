<?php
/**
 * Inscription Client
 * POST /api/auth/register.php
 */

require_once '../config/database.php';

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lire les données JSON
$data = json_decode(file_get_contents("php://input"), true);

// Vérifier si le Content-Type est correct
if ($data === null) {
    echo json_encode([
        "success" => false, 
        "message" => "Données JSON invalides. Vérifiez le Content-Type: application/json"
    ]);
    exit();
}

// 1. VALIDATION DES CHAMPS REQUIS
$required_fields = ['nom', 'prenom', 'email', 'password'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (empty($data[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    echo json_encode([
        "success" => false, 
        "message" => "Champs requis manquants: " . implode(', ', $missing_fields)
    ]);
    exit();
}

// 2. NETTOYAGE DES DONNÉES
$nom = trim($db->escape($data['nom']));
$prenom = trim($db->escape($data['prenom']));
$email = trim(strtolower($db->escape($data['email'])));
$telephone = isset($data['telephone']) ? trim($db->escape($data['telephone'])) : null;
$address = isset($data['address']) ? trim($db->escape($data['address'])) : null;
$password = $data['password'];

// 3. VALIDATION DE L'EMAIL
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Email invalide"]);
    exit();
}

// 4. VALIDATION DU MOT DE PASSE (minimum 6 caractères)
if (strlen($password) < 6) {
    echo json_encode(["success" => false, "message" => "Le mot de passe doit contenir au moins 6 caractères"]);
    exit();
}

// 5. HASHAGE DU MOT DE PASSE
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 6. VÉRIFIER SI L'EMAIL EXISTE DÉJÀ
$checkQuery = "SELECT id, email FROM utilisateurs WHERE email = ?";
$checkStmt = $db->prepare($checkQuery);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé"]);
    $checkStmt->close();
    exit();
}
$checkStmt->close();

// 7. INSERTION DU NOUVEL UTILISATEUR
$sql = "INSERT INTO utilisateurs (nom, prenom, email, telephone, password, role, address, created_at) 
        VALUES (?, ?, ?, ?, ?, 'client', ?, NOW())";

$stmt = $db->prepare($sql);
$stmt->bind_param("ssssss", $nom, $prenom, $email, $telephone, $hashedPassword, $address);

if ($stmt->execute()) {
    $userId = $db->lastInsertId();
    
    // 8. CRÉER LA SESSION
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_nom'] = $nom;
    $_SESSION['user_prenom'] = $prenom;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = 'client';
    $_SESSION['user_address'] = $address;
    
    // 9. RÉPONSE SUCCÈS
    echo json_encode([
        "success" => true,
        "message" => "Inscription réussie ! Bienvenue $prenom $nom",
        "user" => [
            "id" => $userId,
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "role" => "client",
            "address" => $address
        ]
    ]);
} else {
    // 10. ERREUR D'INSERTION
    echo json_encode([
        "success" => false, 
        "message" => "Erreur lors de l'inscription: " . $stmt->error
    ]);
}

$stmt->close();
?>