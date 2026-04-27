<?php
/**
 * Model: Catégorie de Plats
 * Gestion des catégories de menu
 */

require_once __DIR__ . '/BaseModel.php';

class DishCategory extends BaseModel {
    protected $table = 'dish_categories';

    /**
     * Obtenir toutes les catégories
     */
    public function getAll($limit = null, $offset = 0) {
        return parent::getAll($limit, $offset);
    }

    /**
     * Obtenir une catégorie par ID
     */
    public function getById($id) {
        return parent::getById($id);
    }

    /**
     * Créer une catégorie
     */
    public function create($data) {
        return parent::create($data);
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update($id, $data) {
        return parent::update($id, $data);
    }

    /**
     * Supprimer une catégorie
     */
    public function delete($id) {
        return parent::delete($id);
    }

    /**
     * Obtenir les plats d'une catégorie
     */
    public function getDishesByCategory($categoryId, $limit = null, $offset = 0) {
        $query = "SELECT * FROM dishes WHERE category_id = ?";
        
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
     * Obtenir le nombre total de catégories
     */
    public function count() {
        return parent::count();
    }
}
?>
