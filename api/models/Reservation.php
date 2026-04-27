<?php
/**
 * Modèle Réservations
 */

require_once __DIR__ . '/BaseModel.php';

class Reservation extends BaseModel {
    protected $table = 'reservations';

    /**
     * Obtenir les réservations par client
     */
    public function getByClientId($clientId) {
        $query = "
            SELECT r.*, u.first_name, u.last_name, u.email, u.phone
            FROM {$this->table} r
            LEFT JOIN utilisateurs u ON r.client_id = u.id
            WHERE r.client_id = ?
            ORDER BY r.reservation_date DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir les réservations par statut
     */
    public function getByStatus($status, $limit = null, $offset = 0) {
        $query = "
            SELECT r.*, u.first_name, u.last_name, u.email
            FROM {$this->table} r
            LEFT JOIN utilisateurs u ON r.client_id = u.id
            WHERE r.status = ?
            ORDER BY r.reservation_date DESC
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
     * Obtenir les réservations pour une date spécifique
     */
    public function getByDate($date) {
        $query = "
            SELECT r.*, u.first_name, u.last_name, u.email, u.phone
            FROM {$this->table} r
            LEFT JOIN utilisateurs u ON r.client_id = u.id
            WHERE DATE(r.reservation_date) = ?
            ORDER BY r.reservation_time ASC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir les réservations entre deux dates
     */
    public function getByDateRange($startDate, $endDate) {
        $query = "
            SELECT r.*, u.first_name, u.last_name, u.email
            FROM {$this->table} r
            LEFT JOIN utilisateurs u ON r.client_id = u.id
            WHERE DATE(r.reservation_date) BETWEEN ? AND ?
            ORDER BY r.reservation_date, r.reservation_time ASC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir les réservations confirmées
     */
    public function getConfirmedReservations($limit = null, $offset = 0) {
        $query = "
            SELECT r.*, u.first_name, u.last_name, u.email, u.phone
            FROM {$this->table} r
            LEFT JOIN utilisateurs u ON r.client_id = u.id
            WHERE r.status = 'confirmed'
            ORDER BY r.reservation_date, r.reservation_time ASC
        ";

        if ($limit) {
            $query .= " LIMIT {$offset}, {$limit}";
        }

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Vérifier s'il y a de la place à une heure donnée
     */
    public function checkAvailability($date, $time, $guestCount, $capacityPerSlot = 20) {
        $query = "
            SELECT SUM(number_of_guests) as total_guests
            FROM {$this->table}
            WHERE reservation_date = ? 
            AND reservation_time = ?
            AND status IN ('confirmed', 'pending')
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $date, $time);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $totalGuests = $row['total_guests'] ?? 0;
        $availableSpots = $capacityPerSlot - $totalGuests;
        
        return $availableSpots >= $guestCount;
    }

    /**
     * Obtenir les statistiques des réservations
     */
    public function getStatistics() {
        $query = "
            SELECT 
                COUNT(*) as total_reservations,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_reservations,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_reservations,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_reservations,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_reservations,
                SUM(number_of_guests) as total_guests,
                AVG(number_of_guests) as average_guests_per_reservation
            FROM {$this->table}
        ";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    /**
     * Obtenir les détails complets d'une réservation
     */
    public function getFullDetails($reservationId) {
        $query = "
            SELECT r.*, u.first_name, u.last_name, u.email, u.phone, u.address
            FROM {$this->table} r
            LEFT JOIN utilisateurs u ON r.client_id = u.id
            WHERE r.id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $reservationId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Rechercher des réservations
     */
    public function search($term) {
        $query = "
            SELECT r.*, u.first_name, u.last_name, u.email
            FROM {$this->table} r
            LEFT JOIN utilisateurs u ON r.client_id = u.id
            WHERE u.first_name LIKE ? 
            OR u.last_name LIKE ?
            OR u.email LIKE ?
            OR u.phone LIKE ?
            ORDER BY r.reservation_date DESC
        ";
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$term}%";
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtenir toutes avec client info
     */
    public function getAll($limit = null, $offset = 0) {
        $query = "
            SELECT r.*, u.first_name, u.last_name, u.email, u.phone
            FROM {$this->table} r
            LEFT JOIN utilisateurs u ON r.client_id = u.id
            ORDER BY r.reservation_date DESC
        ";

        if ($limit) {
            $query .= " LIMIT {$offset}, {$limit}";
        }

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
