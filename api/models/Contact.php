<?php
/**
 * Modèle Messages de Contact
 */

require_once __DIR__ . '/BaseModel.php';

class Contact extends BaseModel {
    protected $table = 'contact_messages';

    /**
     * Obtenir les messages par statut
     */
    public function getByStatus($status, $limit = null, $offset = 0) {
        $query = "
            SELECT * FROM {$this->table}
            WHERE status = ?
            ORDER BY created_at DESC
        ";

        if ($limit) {
            $query .= " LIMIT {$offset}, {$limit}";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir les nouveaux messages (non lus)
     */
    public function getNewMessages($limit = null, $offset = 0) {
        return $this->getByStatus('new', $limit, $offset);
    }

    /**
     * Marquer comme lu
     */
    public function markAsRead($id) {
        return $this->update($id, ['status' => 'read']);
    }

    /**
     * Marquer comme répondu
     */
    public function markAsReplied($id, $response) {
        return $this->update($id, [
            'status' => 'replied',
            'response' => $response,
            'replied_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Obtenir les messages d'un email
     */
    public function getByEmail($email) {
        $query = "
            SELECT * FROM {$this->table}
            WHERE email = ?
            ORDER BY created_at DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Rechercher des messages
     */
    public function search($term) {
        $query = "
            SELECT * FROM {$this->table}
            WHERE name LIKE ? 
            OR email LIKE ?
            OR subject LIKE ?
            OR message LIKE ?
            ORDER BY created_at DESC
        ";
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$term}%";
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics() {
        $query = "
            SELECT 
                COUNT(*) as total_messages,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_messages,
                SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read_messages,
                SUM(CASE WHEN status = 'replied' THEN 1 ELSE 0 END) as replied_messages,
                COUNT(DISTINCT email) as unique_contacts
            FROM {$this->table}
        ";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    /**
     * Obtenir les derniers messages
     */
    public function getLatest($limit = 10) {
        $query = "
            SELECT * FROM {$this->table}
            ORDER BY created_at DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Supprimer les messages anciens
     */
    public function deleteOlderThan($days = 90) {
        $date = date('Y-m-d', strtotime("-{$days} days"));
        $query = "DELETE FROM {$this->table} WHERE created_at < ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $date);
        return $stmt->execute();
    }

    /**
     * Obtenir toutes les entités
     */
    public function getAll($limit = null, $offset = 0) {
        $query = "
            SELECT * FROM {$this->table}
            ORDER BY created_at DESC
        ";

        if ($limit) {
            $query .= " LIMIT {$offset}, {$limit}";
        }

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
