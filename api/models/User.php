<?php
/**
 * Modèle Utilisateur
 * Gère les clients et administrateurs dans une seule table
 */

require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    protected $table = 'utilisateurs';

    /**
     * Créer un nouvel utilisateur
     */
    public function create($data) {
        return parent::create($data);
    }

    /**
     * Obtenir tous les utilisateurs avec un rôle spécique
     */
    public function getByRole($role) {
        $query = "SELECT * FROM {$this->table} WHERE role = ? AND is_active = TRUE";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir un utilisateur par email
     */
    public function getByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Obtenir un utilisateur par ID
     */
    public function getById($id) {
        return parent::getById($id);
    }

    /**
     * Obtenir tous les clients
     */
    public function getAllClients($limit = null, $offset = 0) {
        $query = "SELECT * FROM {$this->table} WHERE role = 'client' AND is_active = TRUE";
        
        if ($limit) {
            $query .= " LIMIT {$offset}, {$limit}";
        }
        
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtenir tous les administrateurs
     */
    public function getAllAdmins($limit = null, $offset = 0) {
        $query = "SELECT * FROM {$this->table} WHERE role IN ('admin', 'manager', 'staff') AND is_active = TRUE";
        
        if ($limit) {
            $query .= " LIMIT {$offset}, {$limit}";
        }
        
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Vérifier les identifiants (pour login)
     */
    public function authenticate($email, $password) {
        $user = $this->getByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }
        
        if (!$user['is_active']) {
            return null;
        }
        
        return $user;
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update($id, $data) {
        return parent::update($id, $data);
    }

    /**
     * Désactiver un utilisateur (soft delete)
     */
    public function deactivate($id) {
        return $this->update($id, ['is_active' => false]);
    }

    /**
     * Activer un utilisateur
     */
    public function activate($id) {
        return $this->update($id, ['is_active' => true]);
    }

    /**
     * Changer le rôle d'un utilisateur
     */
    public function changeRole($id, $newRole) {
        if (!in_array($newRole, ['client', 'staff', 'manager', 'admin'])) {
            return false;
        }
        return $this->update($id, ['role' => $newRole]);
    }

    /**
     * Compter les utilisateurs par rôle
     */
    public function countByRole($role) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE role = ? AND is_active = TRUE";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }

    /**
     * Supprimer un utilisateur (hard delete)
     */
    public function delete($id) {
        return parent::delete($id);
    }

    /**
     * Obtenir les statistiques des utilisateurs
     */
    public function getStatistics() {
        $query = "
            SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN role = 'client' THEN 1 ELSE 0 END) as total_clients,
                SUM(CASE WHEN role IN ('admin', 'manager', 'staff') THEN 1 ELSE 0 END) as total_admins,
                SUM(CASE WHEN is_active = TRUE THEN 1 ELSE 0 END) as active_users,
                SUM(CASE WHEN is_active = FALSE THEN 1 ELSE 0 END) as inactive_users
            FROM {$this->table}
        ";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    /**
     * Rechercher des utilisateurs
     */
    public function search($term) {
        $query = "
            SELECT * FROM {$this->table} 
            WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?)
            AND is_active = TRUE
        ";
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$term}%";
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
