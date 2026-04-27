<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Connexion BDD
$host = "localhost";
$dbname = "benna_tounsiya";  // ← Mets le nom de TA BDD
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur base de données"]);
    exit();
}

// Lire la méthode et l'action
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// ============ ROUTER ============
if ($method === 'POST') {
    sendMessage($pdo);
} 
elseif ($method === 'GET' && $action === 'list') {
    getAllMessages($pdo);
}
elseif ($method === 'GET' && $action === 'get' && isset($_GET['id'])) {
    getMessageById($pdo, $_GET['id']);
}
elseif ($method === 'DELETE' && $action === 'delete' && isset($_GET['id'])) {
    deleteMessage($pdo, $_GET['id']);
}
elseif ($method === 'POST' && $action === 'reply' && isset($_GET['id'])) {
    replyToMessage($pdo, $_GET['id']);
}
else {
    echo json_encode(["success" => false, "message" => "Action non trouvée"]);
}

// ============ FONCTIONS ============

function sendMessage($pdo) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Vérifier les données
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Données invalides"]);
        return;
    }
    
    // Vérifier les champs requis
    if (empty($data['name']) || empty($data['email']) || empty($data['subject']) || empty($data['message'])) {
        echo json_encode(["success" => false, "message" => "Veuillez remplir tous les champs"]);
        return;
    }
    
    // Vérifier email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Email invalide"]);
        return;
    }
    
    try {
        $sql = "INSERT INTO contact_messages (name, email, phone, subject, message, status) 
                VALUES (:name, :email, :phone, :subject, :message, 'new')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':subject' => $data['subject'],
            ':message' => $data['message']
        ]);
        
        echo json_encode([
            "success" => true, 
            "message" => "✅ Message envoyé avec succès !"
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => "Erreur: " . $e->getMessage()]);
    }
}

function getAllMessages($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        $messages = $stmt->fetchAll();
        echo json_encode(["success" => true, "data" => $messages]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function getMessageById($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        $message = $stmt->fetch();
        
        if ($message) {
            echo json_encode(["success" => true, "data" => $message]);
        } else {
            echo json_encode(["success" => false, "message" => "Message non trouvé"]);
        }
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function deleteMessage($pdo, $id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Message supprimé"]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function replyToMessage($pdo, $id) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['response'])) {
        echo json_encode(["success" => false, "message" => "La réponse est requise"]);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'replied', response = :response, replied_at = NOW() WHERE id = :id");
        $stmt->execute([':response' => $data['response'], ':id' => $id]);
        echo json_encode(["success" => true, "message" => "Réponse envoyée"]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>