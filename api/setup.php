<?php
/**
 * Script d'Installation et Configuration Initial
 * À exécuter une seule fois pour initialiser le projet
 * Accéder à: http://localhost/api/setup.php
 */

// Configuration de la base de données
$db_config = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'db_name' => 'benna_tounsiya'
];

// Connexion à MySQL
try {
    $conn = new mysqli($db_config['host'], $db_config['user'], $db_config['pass']);
    
    if ($conn->connect_error) {
        throw new Exception("Erreur de connexion: " . $conn->connect_error);
    }
    
    echo "<h1>✅ Installation - Benna Tounsiya</h1>";
    
    // 1. Vérifier si la base existe
    $result = $conn->query("SHOW DATABASES LIKE '{$db_config['db_name']}'");
    
    if ($result->num_rows == 0) {
        echo "<p>Création de la base de données...</p>";
        
        $sql_file = __DIR__ . '/benna_tounsiya.sql';
        if (!file_exists($sql_file)) {
            throw new Exception("Fichier benna_tounsiya.sql introuvable");
        }
        
        // Lire le fichier SQL
        $sql_content = file_get_contents($sql_file);
        
        // Exécuter le SQL
        if ($conn->multi_query($sql_content)) {
            while ($conn->next_result()) {
                ;
            }
            echo "<p style='color: green;'>✅ Base de données créée avec succès</p>";
        } else {
            throw new Exception("Erreur lors de l'exécution du SQL: " . $conn->error);
        }
    } else {
        echo "<p style='color: blue;'>ℹ️ Base de données déjà existante</p>";
    }
    
    // 2. Se connecter à la base
    $conn->select_db($db_config['db_name']);
    
    // 3. Vérifier les tables
    $tables = [
        'utilisateurs',
        'dish_categories',
        'dishes',
        'reservations',
        'orders',
        'order_items',
        'contacts'
    ];
    
    echo "<h2>Vérification des tables:</h2><ul>";
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '{$table}'");
        if ($result->num_rows > 0) {
            echo "<li>✅ $table</li>";
        } else {
            echo "<li>❌ $table (manquante)</li>";
        }
    }
    echo "</ul>";
    
    // 4. Vérifier les utilisateurs admin
    $result = $conn->query("SELECT COUNT(*) as count FROM utilisateurs WHERE role = 'admin'");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        echo "<h2>Création d'un utilisateur Admin...</h2>";
        
        $admin_email = 'admin@bennatounsiya.tn';
        $admin_password = password_hash('admin123456', PASSWORD_BCRYPT, ['cost' => 10]);
        
        $stmt = $conn->prepare("INSERT INTO utilisateurs (first_name, last_name, email, password, phone, role, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $first_name = 'Admin';
        $last_name = 'Benna';
        $phone = '+216 20 000 000';
        $role = 'admin';
        $is_active = 1;
        
        $stmt->bind_param(
            "ssssssi",
            $first_name,
            $last_name,
            $admin_email,
            $admin_password,
            $phone,
            $role,
            $is_active
        );
        
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✅ Admin créé</p>";
            echo "<p><strong>Email:</strong> $admin_email</p>";
            echo "<p><strong>Mot de passe:</strong> admin123456</p>";
            echo "<p style='color: red;'><strong>⚠️ À CHANGER APRÈS CONNEXION!</strong></p>";
        } else {
            throw new Exception("Erreur création admin: " . $conn->error);
        }
    } else {
        echo "<p style='color: green;'>✅ Admin(s) déjà créé(s)</p>";
    }
    
    // 5. Vérifier les catégories et plats
    $result = $conn->query("SELECT COUNT(*) as count FROM dish_categories");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        echo "<h2>Ajout de catégories et plats de test...</h2>";
        
        // Catégories
        $categories = [
            ['Traditionnel', 'تقليدي'],
            ['Fruits de Mer', 'المأكولات البحرية'],
            ['Entrées', 'المقبلات'],
            ['Desserts', 'الحلويات']
        ];
        
        foreach ($categories as $cat) {
            $conn->prepare("INSERT INTO dish_categories (name, name_ar) VALUES (?, ?)")->bind_param("ss", $cat[0], $cat[1])->execute();
        }
        
        echo "<p>✅ Catégories ajoutées</p>";
        
        // Plats de test
        $conn->query("
            INSERT INTO dishes (category_id, name, name_ar, description, price, is_available, rating)
            VALUES
            (1, 'Couscous', 'كسكسي', 'Couscous traditionnel tunisien', 12.50, 1, 4.5),
            (1, 'Brik à l\'Oeuf', 'برك بالبيضة', 'Brik traditionnel', 5.00, 1, 4.8),
            (2, 'Salade de Fruits de Mer', 'سلطة المأكولات البحرية', 'Mélange frais', 15.00, 1, 4.6),
            (3, 'Hummus', 'حمص', 'Purée de pois chiches', 4.50, 1, 4.4),
            (4, 'Makrout', 'مقروط', 'Gâteau aux dattes', 3.00, 1, 4.7)
        ");
        
        echo "<p>✅ Plats de test ajoutés</p>";
    } else {
        echo "<p style='color: blue;'>ℹ️ Catégories et plats déjà existants</p>";
    }
    
    // 6. Afficher le résumé
    echo "<h2 style='color: green; margin-top: 30px;'>✅ Installation Complète!</h2>";
    echo "<div style='background: #e8f5e9; padding: 20px; border-radius: 5px;'>";
    echo "<h3>Prochaines étapes:</h3>";
    echo "<ol>";
    echo "<li>Vérifier que le point de terminaison API fonctionne: <code>http://localhost/api/health</code></li>";
    echo "<li>Tester la connexion: <code>POST /api/auth/login</code></li>";
    echo "<li>Installer les dépendances npm: <code>npm install</code></li>";
    echo "<li>Démarrer le frontend: <code>npm run dev</code></li>";
    echo "</ol>";
    echo "</div>";
    
    // Nettoyer
    echo "<p style='margin-top: 30px; color: red;'><strong>⚠️ IMPORTANT:</strong> Supprimez ce fichier (setup.php) après utilisation ou mettez-le hors ligne.</p>";
    
} catch (Exception $e) {
    echo "<h1 style='color: red;'>❌ Erreur</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}

$conn->close();
?>
