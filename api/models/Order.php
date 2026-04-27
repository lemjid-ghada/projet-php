<?php
/**
 * Modèle Commandes
 */

require_once __DIR__ . '/BaseModel.php';

class Order extends BaseModel {
    protected $table = 'orders';

    /**
     * Obtenir les commandes par client
     */
    public function getByClientId($clientId) {
        $query = "
            SELECT o.*, u.first_name, u.last_name, u.email
            FROM {$this->table} o
            LEFT JOIN utilisateurs u ON o.client_id = u.id
            WHERE o.client_id = ?
            ORDER BY o.created_at DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir les commandes par statut
     */
    public function getByStatus($status, $limit = null, $offset = 0) {
        $query = "
            SELECT o.*, u.first_name, u.last_name, u.email
            FROM {$this->table} o
            LEFT JOIN utilisateurs u ON o.client_id = u.id
            WHERE o.status = ?
            ORDER BY o.created_at DESC
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
     * Ajouter un item à une commande
     */
    public function addItem($orderId, $data) {
        $keys = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($keys), '?');
        
        $query = "INSERT INTO order_items (order_id, " . implode(', ', $keys) . ") VALUES (?, " . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($query);
        $types = 'i' . $this->getTypes($values);
        $params = array_merge([$orderId], $values);
        $stmt->bind_param($types, ...$params);
        
        return $stmt->execute();
    }

    /**
     * Obtenir les items d'une commande
     */
    public function getItems($orderId) {
        $query = "
            SELECT oi.*, d.name, d.name_ar, d.description
            FROM order_items oi
            LEFT JOIN dishes d ON oi.dish_id = d.id
            WHERE oi.order_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Supprimer les items d'une commande
     */
    public function deleteItems($orderId) {
        $query = "DELETE FROM order_items WHERE order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $orderId);
        return $stmt->execute();
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics() {
        $query = "
            SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                SUM(total_amount) as total_revenue,
                AVG(total_amount) as average_order_value,
                MAX(total_amount) as highest_order_value
            FROM {$this->table}
        ";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    /**
     * Obtenir tous les items de commande avec détails
     */
    public function getAllItems() {
        $query = "
            SELECT oi.*, d.name, d.description, o.id as order_id, o.status
            FROM order_items oi
            LEFT JOIN dishes d ON oi.dish_id = d.id
            LEFT JOIN orders o ON oi.order_id = o.id
            ORDER BY oi.id DESC
        ";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir les détails d'une commande avec items et client
     */
    public function getFullDetails($orderId) {
        $query = "
            SELECT o.*, u.first_name, u.last_name, u.email, u.phone, u.address
            FROM {$this->table} o
            LEFT JOIN utilisateurs u ON o.client_id = u.id
            WHERE o.id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        if ($order) {
            $order['items'] = $this->getItems($orderId);
        }

        return $order;
    }

    /**
     * Helper pour obtenir les types de données
     */
    private function getTypes($values) {
        $types = '';
        foreach ($values as $value) {
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_float($value)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        return $types;
    }
}
