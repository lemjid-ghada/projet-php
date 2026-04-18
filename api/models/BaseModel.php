<?php
/**
 * Classe de Base Model
 * Toutes les models hériteront de cette classe
 */

abstract class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtenir tous les enregistrements
     */
    protected function getAll($limit = null, $offset = 0) {
        $query = "SELECT * FROM {$this->table}";
        
        if ($limit) {
            $query .= " LIMIT {$offset}, {$limit}";
        }
        
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtenir par ID
     */
    protected function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Obtenir le nombre total
     */
    protected function count() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }

    /**
     * Créer un enregistrement
     */
    protected function create($data) {
        $keys = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($keys), '?');
        
        $query = "INSERT INTO {$this->table} (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($query);
        $types = $this->getTypes($values);
        $stmt->bind_param($types, ...$values);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        
        return false;
    }

    /**
     * Mettre à jour un enregistrement
     */
    protected function update($id, $data) {
        $sets = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $sets[] = "$key = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        $types = $this->getTypes($values);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }

    /**
     * Supprimer un enregistrement
     */
    protected function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Obtenir les types de données pour bind_param
     */
    protected function getTypes($values) {
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
?>
