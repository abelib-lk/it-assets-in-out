<?php
// controllers/DashboardController.php
require_once 'config/database.php';

class DashboardController {
    private $db;

    public function __construct() {
        check_auth(); // Ensure logged in
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        // Gather stats
        $stats = [
            'total_assets' => 0,
            'checked_out' => 0,
            'overdue' => 0,
            'categories' => []
        ];

        // Total Assets
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM assets");
        $stats['total_assets'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Checked Out
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM assets WHERE status = 'Checked Out'");
        $stats['checked_out'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Overdue (active transactions where expected < now)
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM transactions WHERE actual_return_date IS NULL AND expected_return_date < NOW()");
        $stats['overdue'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // By Category
        $stmt = $this->db->query("SELECT c.name, COUNT(a.id) as count FROM assets a JOIN categories c ON a.category_id = c.id GROUP BY c.name");
        $stats['categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'views/dashboard/index.php';
    }
}
