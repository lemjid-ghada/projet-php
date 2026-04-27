# 📋 Guide de Migration : Fusion des tables Clients et Admins

## Vue d'ensemble

Ce guide explique comment passer d'une architecture avec deux tables séparées (`clients` et `admins`) à une architecture unifiée avec une seule table `utilisateurs` utilisant un champ `role` pour différencier les types d'utilisateurs.

---

## 📊 Changements dans la structure de base de données

### Avant (Ancien modèle)
```sql
-- Deux tables séparées
CREATE TABLE clients (...)
CREATE TABLE admins (...)

-- References pointaient vers clients
ALTER TABLE reservations ADD FOREIGN KEY (client_id) REFERENCES clients(id);
ALTER TABLE orders ADD FOREIGN KEY (client_id) REFERENCES clients(id);
ALTER TABLE reviews ADD FOREIGN KEY (client_id) REFERENCES clients(id);
```

### Après (Nouveau modèle)
```sql
-- Une seule table unifiée
CREATE TABLE utilisateurs (
  id INT PRIMARY KEY,
  ...
  role ENUM('client', 'staff', 'manager', 'admin') DEFAULT 'client',
  ...
)

-- References pointent vers utilisateurs
ALTER TABLE reservations ADD FOREIGN KEY (client_id) REFERENCES utilisateurs(id);
ALTER TABLE orders ADD FOREIGN KEY (client_id) REFERENCES utilisateurs(id);
ALTER TABLE reviews ADD FOREIGN KEY (client_id) REFERENCES utilisateurs(id);
```

---

## 🔄 Étapes de migration

### Étape 1 : Créer la table `utilisateurs`
```bash
# Le fichier `benna_tounsiya.sql` contient déjà la nouvelle structure
# Vous pouvez aussi exécuter:
cat migration_users.sql | mysql -u root -p benna_tounsiya
```

### Étape 2 : Migrer les données existantes
```bash
# Exécutez le script de migration
mysql -u root -p benna_tounsiya < migration_users.sql
```

### Étape 3 : Mettre à jour les références
Les foreign keys sont automatiquement mises à jour par le script de migration.

### Étape 4 : Supprimer les anciennes tables (optionnel)
```sql
-- Après vérification que tout fonctionne bien
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS admins;
```

---

## 🎯 Rôles disponibles

La table `utilisateurs` utilise les rôles suivants :

| Rôle | Description | Permissions |
|------|-------------|-------------|
| `client` | Client régulier | Peut faire des réservations et des commandes |
| `staff` | Personnel | Peut gérer les commandes et les réservations |
| `manager` | Gestionnaire | Accès complet aux opérations |
| `admin` | Administrateur | Accès total (création d'utilisateurs, paramètres) |

---

## 💻 Changements dans le code PHP

### Avant : Modèles séparés
```php
// ClientModel.php
class Client extends BaseModel {
    protected $table = 'clients';
}

// AdminModel.php
class Admin extends BaseModel {
    protected $table = 'admins';
}
```

### Après : Modèle unifié
```php
// User.php
class User extends BaseModel {
    protected $table = 'utilisateurs';
    
    // Récupérer par rôle
    public function getByRole($role) { ... }
    
    // Récupérer tous les clients
    public function getAllClients() { ... }
    
    // Récupérer tous les administrateurs
    public function getAllAdmins() { ... }
    
    // Changer le rôle
    public function changeRole($id, $role) { ... }
}
```

---

## 🔌 Utilisation dans les contrôleurs

### Créer un nouvel utilisateur
```php
$userController = new UserController();

// Client
$data = [
    'first_name' => 'Ahmed',
    'last_name' => 'Ben Ali',
    'email' => 'ahmed@example.com',
    'password' => 'secure_password',
    'phone' => '+216 50 123 456',
    'role' => 'client'
];
$userController->create($data);

// Admin
$data['role'] = 'admin';
$userController->create($data);
```

### Récupérer les utilisateurs
```php
// Tous les clients
$userController->getAllClients();

// Tous les administrateurs
$userController->getAllAdmins();

// Utilisateur spécifique
$userController->getById(1);
```

### Changer le rôle d'un utilisateur
```php
$userController->changeRole(5, 'manager');
```

### Désactiver/Activer un utilisateur
```php
// Désactiver
$userController->deactivate(5);

// Activer
$userController->activate(5);
```

---

## 🔐 Authentification

L'authentification fonctionne maintenant de la même manière pour les clients et les administrateurs :

```php
$user = $userModel->authenticate($email, $password);

if ($user) {
    // Vérifier le rôle
    if ($user['role'] === 'admin') {
        // Accès administrateur
    } elseif ($user['role'] === 'client') {
        // Accès client
    }
}
```

---

## 📊 Points de données importants

### Statistiques des utilisateurs
```php
$stats = $userModel->getStatistics();
// Retourne:
// {
//   "total_users": 50,
//   "total_clients": 45,
//   "total_admins": 5,
//   "active_users": 48,
//   "inactive_users": 2
// }
```

### Recherche d'utilisateurs
```php
$results = $userModel->search('Ahmed');
// Recherche par: first_name, last_name, email, phone
```

---

## 🔄 Requêtes SQL utiles

### Obtenir tous les clients actifs
```sql
SELECT * FROM utilisateurs 
WHERE role = 'client' AND is_active = TRUE
ORDER BY created_at DESC;
```

### Obtenir tous les administrateurs
```sql
SELECT * FROM utilisateurs 
WHERE role IN ('admin', 'manager', 'staff')
ORDER BY role DESC;
```

### Compter les nouveaux clients ce mois
```sql
SELECT COUNT(*) FROM utilisateurs 
WHERE role = 'client' 
AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
AND created_at < DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL 1 MONTH);
```

### Promouvoir un client en staff
```sql
UPDATE utilisateurs 
SET role = 'staff' 
WHERE id = 5;
```

---

## 🚀 Endpoints API

### UserController Routes

```
POST   /api/users                  - Créer un nouvel utilisateur
GET    /api/users                  - Obtenir tous les utilisateurs
GET    /api/users/:id              - Obtenir un utilisateur
GET    /api/users/clients          - Obtenir tous les clients
GET    /api/users/admins           - Obtenir tous les administrateurs
PUT    /api/users/:id              - Mettre à jour un utilisateur
POST   /api/users/:id/role         - Changer le rôle
POST   /api/users/:id/deactivate   - Désactiver l'utilisateur
POST   /api/users/:id/activate     - Activer l'utilisateur
DELETE /api/users/:id              - Supprimer l'utilisateur
GET    /api/users/search?q=term    - Rechercher des utilisateurs
GET    /api/users/statistics       - Obtenir les statistiques
```

---

## ⚠️ Points d'attention

1. **Backup** : Toujours faire un backup avant de migrer les données
2. **Tests** : Tester complètement après la migration
3. **Email unique** : Les emails restent uniques dans la nouvelle table
4. **Cascade delete** : Les réservations et commandes seront supprimées si l'utilisateur est supprimé
5. **Rôles par défaut** : Les nouveaux utilisateurs sont créés avec le rôle 'client' par défaut

---

## ✅ Checklist de migration

- [ ] Backup de la base de données actuelle
- [ ] Exécution du script de migration
- [ ] Vérification des données migrées
- [ ] Test des authentifications clients
- [ ] Test des authentifications administrateurs
- [ ] Vérification des réservations et commandes
- [ ] Test de l'API UserController
- [ ] Suppression des anciennes tables (optionnel)
- [ ] Mise à jour de la documentation interne
- [ ] Déploiement en production

---

## 📞 Support

Pour toute question concernant la migration, veuillez consulter les fichiers suivants :

- `benna_tounsiya.sql` - Nouvelle structure complète
- `migration_users.sql` - Script de migration
- `api/models/User.php` - Modèle unifié
- `api/controllers/UserController.php` - Contrôleur unifié
