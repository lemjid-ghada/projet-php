<?php
/**
 * Model: Plats
 * Gestion des plats du menu
 */

require_once __DIR__ . '/BaseModel.php';

class Dish extends BaseModel {
    protected $table = 'dishes';

    /**
     * Obtenir tous les plats
     */
    public function getAll($limit = null, $offset = 0) {
        return parent::getAll($limit, $offset);
    }

    /**
     * Obtenir un plat par ID
     */
    public function getById($id) {
        return parent::getById($id);
    }

    /**
     * Créer un plat
     */
    public function create($data) {
        return parent::create($data);
    }

    /**
     * Mettre à jour un plat
     */
    public function update($id, $data) {
        return parent::update($id, $data);
    }

    /**
     * Supprimer un plat
     */
    public function delete($id) {
        return parent::delete($id);
    }

    /**
     * Obtenir les plats disponibles
     */
    public function getAvailable($limit = null, $offset = 0) {
        $query = "SELECT * FROM {$this->table} WHERE is_available = 1";
        
        if ($limit) {
            $query .= " LIMIT {$offset}, {$limit}";
        }
        
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtenir les plats d'une catégorie
     */
    public function getByCategory($categoryId, $limit = null, $offset = 0) {
        $query = "SELECT * FROM {$this->table} WHERE category_id = ?";
        
        if ($limit) {
            $query .= " LIMIT {$offset}, {$limit}";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Rechercher des plats
     */
    public function search($keyword, $limit = 20, $offset = 0) {
        $keyword = "%{$keyword}%";
        $query = "SELECT * FROM {$this->table} 
                  WHERE name LIKE ? OR description LIKE ? OR name_ar LIKE ? 
                  LIMIT ?, ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssii", $keyword, $keyword, $keyword, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir les plats épicés
     */
    public function getSpicy($limit = null, $offset = 0) {
        $query = "SELECT * FROM {$this->table} WHERE is_spicy = 1";
        
        if ($limit) {
            $query .= " LIMIT {$offset}, {$limit}";
        }
        
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtenir les plats avec note minimum
     */
    public function getTopRated($minRating = 4, $limit = 10) {
        $query = "SELECT * FROM {$this->table} WHERE rating >= ? ORDER BY rating DESC LIMIT ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("di", $minRating, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir le nombre total de plats disponibles
     */
    public function countAvailable() {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE is_available = 1";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
}
?>
