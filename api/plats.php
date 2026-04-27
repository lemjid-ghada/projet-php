<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=utf-8");

// Gestion de la requête PREFLIGHT (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Connexion BDD
$host = "localhost";
$dbname = "benna_tounsiya";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur BDD: " . $e->getMessage()]);
    exit();
}

// Router
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    // ============ MÉTHODES GET ============
    if ($method === 'GET' && $action === 'categories') {
        getAllCategories($pdo);
    }
    elseif ($method === 'GET' && $action === 'category' && isset($_GET['id'])) {
        getPlatsByCategory($pdo, $_GET['id']);
    }
    elseif ($method === 'GET' && isset($_GET['id'])) {
        getPlatById($pdo, $_GET['id']);
    }
    elseif ($method === 'GET') {
        getAllPlats($pdo);
    }
    
    // ============ MÉTHODES POST (Créer) ============
    elseif ($method === 'POST' && $action === 'create') {
        createPlat($pdo);
    }
    elseif ($method === 'POST' && $action === 'create-category') {
        createCategory($pdo);
    }
    
    // ============ MÉTHODES PUT (Modifier) ============
    elseif ($method === 'PUT' && $action === 'update' && isset($_GET['id'])) {
        updatePlat($pdo, $_GET['id']);
    }
    elseif ($method === 'PUT' && $action === 'update-category' && isset($_GET['id'])) {
        updateCategory($pdo, $_GET['id']);
    }
    
    // ============ MÉTHODES DELETE (Supprimer) ============
    elseif ($method === 'DELETE' && $action === 'delete' && isset($_GET['id'])) {
        deletePlat($pdo, $_GET['id']);
    }
    elseif ($method === 'DELETE' && $action === 'delete-category' && isset($_GET['id'])) {
        deleteCategory($pdo, $_GET['id']);
    }
    
    else {
        echo json_encode(["success" => false, "message" => "Action non trouvée"]);
    }
} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur: " . $e->getMessage()]);
}

// ============ FONCTIONS GET ============

function getAllPlats($pdo) {
    try {
        $sql = "SELECT p.*, c.name as categorie_nom 
                FROM plats p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY c.name, p.nom";
        
        $stmt = $pdo->query($sql);
        $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(["success" => true, "data" => $plats]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function getPlatById($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM plats WHERE id = ?");
        $stmt->execute([$id]);
        $plat = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($plat) {
            echo json_encode(["success" => true, "data" => $plat]);
        } else {
            echo json_encode(["success" => false, "message" => "Plat non trouvé"]);
        }
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function getPlatsByCategory($pdo, $category_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM plats WHERE category_id = ?");
        $stmt->execute([$category_id]);
        $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(["success" => true, "data" => $plats]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function getAllCategories($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(["success" => true, "data" => $categories]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

// ============ FONCTIONS CREATE ============

function createPlat($pdo) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Données invalides"]);
        return;
    }
    
    if (empty($data['nom']) || empty($data['prix']) || empty($data['category_id'])) {
        echo json_encode(["success" => false, "message" => "Nom, prix et catégorie sont requis"]);
        return;
    }
    
    try {
        $sql = "INSERT INTO plats (nom, prix, prep_time, category_id, description, image_url) 
                VALUES (:nom, :prix, :prep_time, :category_id, :description, :image_url)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $data['nom'],
            ':prix' => $data['prix'],
            ':prep_time' => $data['prep_time'] ?? 30,
            ':category_id' => $data['category_id'],
            ':description' => $data['description'] ?? null,
            ':image_url' => $data['image_url'] ?? null
        ]);
        
        echo json_encode([
            "success" => true,
            "message" => "Plat ajouté avec succès",
            "id" => $pdo->lastInsertId()
        ]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function createCategory($pdo) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data || empty($data['name'])) {
        echo json_encode(["success" => false, "message" => "Nom de catégorie requis"]);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->execute([':name' => $data['name']]);
        
        echo json_encode([
            "success" => true,
            "message" => "Catégorie ajoutée avec succès",
            "id" => $pdo->lastInsertId()
        ]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

// ============ FONCTIONS UPDATE ============

function updatePlat($pdo, $id) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Données invalides"]);
        return;
    }
    
    try {
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data['nom'])) {
            $fields[] = "nom = :nom";
            $params[':nom'] = $data['nom'];
        }
        if (isset($data['prix'])) {
            $fields[] = "prix = :prix";
            $params[':prix'] = $data['prix'];
        }
        if (isset($data['prep_time'])) {
            $fields[] = "prep_time = :prep_time";
            $params[':prep_time'] = $data['prep_time'];
        }
        if (isset($data['category_id'])) {
            $fields[] = "category_id = :category_id";
            $params[':category_id'] = $data['category_id'];
        }
        if (isset($data['description'])) {
            $fields[] = "description = :description";
            $params[':description'] = $data['description'];
        }
        if (isset($data['image_url'])) {
            $fields[] = "image_url = :image_url";
            $params[':image_url'] = $data['image_url'];
        }
        
        if (empty($fields)) {
            echo json_encode(["success" => false, "message" => "Aucune donnée à mettre à jour"]);
            return;
        }
        
        $sql = "UPDATE plats SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        echo json_encode(["success" => true, "message" => "Plat mis à jour"]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function updateCategory($pdo, $id) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data || empty($data['name'])) {
        echo json_encode(["success" => false, "message" => "Nom requis"]);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
        $stmt->execute([':name' => $data['name'], ':id' => $id]);
        
        echo json_encode(["success" => true, "message" => "Catégorie mise à jour"]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

// ============ FONCTIONS DELETE ============

function deletePlat($pdo, $id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM plats WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(["success" => true, "message" => "Plat supprimé"]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function deleteCategory($pdo, $id) {
    try {
        // Vérifier si la catégorie a des plats
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM plats WHERE category_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            echo json_encode(["success" => false, "message" => "Impossible de supprimer : $count plat(s) utilisent cette catégorie"]);
            return;
        }
        
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(["success" => true, "message" => "Catégorie supprimée"]);
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>