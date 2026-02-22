<?php
// models/Transaction.php
class Transaction {
    private $conn;
    private $table_name = "transactions";

    public $id;
    public $asset_id;
    public $user_id;
    public $admin_id;
    public $action;
    public $expected_return_date;
    public $actual_return_date;
    public $notes;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function checkout() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET asset_id=:asset_id, user_id=:user_id, admin_id=:admin_id, 
                      action='checkout', transaction_date=NOW(), expected_return_date=:expected_return_date, notes=:notes";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":asset_id", $this->asset_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":admin_id", $this->admin_id);
        $stmt->bindParam(":expected_return_date", $this->expected_return_date);
        $stmt->bindParam(":notes", $this->notes);
        
        return $stmt->execute();
    }

    public function request() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET asset_id=:asset_id, user_id=:user_id, 
                      action='request', transaction_date=NOW(), expected_return_date=:expected_return_date, notes=:notes";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":asset_id", $this->asset_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":expected_return_date", $this->expected_return_date);
        $stmt->bindParam(":notes", $this->notes);
        
        return $stmt->execute();
    }

    public function approve($transaction_id, $admin_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET action='checkout', admin_id=:admin_id 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":admin_id", $admin_id);
        $stmt->bindParam(":id", $transaction_id);
        return $stmt->execute();
    }

    public function reject($transaction_id) {
        // For simplicity, we can delete the request or mark as rejected.
        // Let's Detele for now or we need a status column (but we use ENUM action).
        // Let's delete it.
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id AND action='request'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $transaction_id);
        return $stmt->execute();
    }

    public function getPendingRequests() {
         $query = "SELECT t.*, a.asset_tag, a.name as asset_name, u.name as user_name 
                  FROM " . $this->table_name . " t
                  JOIN assets a ON t.asset_id = a.id
                  JOIN users u ON t.user_id = u.id
                  WHERE t.action = 'request'
                  ORDER BY t.transaction_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function checkin($transaction_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET action='checkin', actual_return_date=NOW() 
                  WHERE id=:id"; // Actually, we usually create a NEW transaction record for checkin OR update the checkout record to show it's returned.
                  // Req says: "On check‑in: IT Staff finds the transaction... and marks as returned".
                  // So we update the existing checkout record.
        
        // Wait, if we act on "checkin" as a new transaction, we store history.
        // But the requirement says "marks as returned". 
        // Let's UPDATE the existing checkout transaction to set actual_return_date.
        // AND validation: Check if it's already returned.
        
        $query = "UPDATE " . $this->table_name . " 
                  SET actual_return_date=NOW() 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $transaction_id);
        return $stmt->execute();
    }

    public function getActiveTransactions() {
        // Assets currently checked out
        $query = "SELECT t.*, a.asset_tag, a.name as asset_name, u.name as user_name 
                  FROM " . $this->table_name . " t
                  JOIN assets a ON t.asset_id = a.id
                  JOIN users u ON t.user_id = u.id
                  WHERE t.actual_return_date IS NULL AND t.action = 'checkout'
                  ORDER BY t.expected_return_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getHistoryByAsset($asset_id) {
        $query = "SELECT t.*, u.name as user_name, admin.name as admin_name 
                  FROM " . $this->table_name . " t
                  LEFT JOIN users u ON t.user_id = u.id
                  LEFT JOIN users admin ON t.admin_id = admin.id
                  WHERE t.asset_id = :asset_id
                  ORDER BY t.transaction_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":asset_id", $asset_id);
        $stmt->execute();
        return $stmt;
    }

    public function getUserRequests($user_id) {
        $query = "SELECT t.*, a.asset_tag, a.name as asset_name
                  FROM " . $this->table_name . " t
                  JOIN assets a ON t.asset_id = a.id
                  WHERE t.user_id = :user_id
                  ORDER BY t.transaction_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function getActiveTransactionByAssetTag($asset_tag) {
        $query = "SELECT t.*, a.id as asset_id, a.asset_tag 
                  FROM " . $this->table_name . " t
                  JOIN assets a ON t.asset_id = a.id
                  WHERE a.asset_tag = :asset_tag 
                  AND t.action = 'checkout' 
                  AND t.actual_return_date IS NULL
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":asset_tag", $asset_tag);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTransactionsByDateRange($start_date, $end_date) {
        $query = "SELECT t.*, a.asset_tag, a.name as asset_name, u.name as user_name, admin.name as admin_name
                  FROM " . $this->table_name . " t
                  LEFT JOIN assets a ON t.asset_id = a.id
                  LEFT JOIN users u ON t.user_id = u.id
                  LEFT JOIN users admin ON t.admin_id = admin.id
                  WHERE t.transaction_date BETWEEN :start_date AND :end_date
                  ORDER BY t.transaction_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":start_date", $start_date);
        $stmt->bindParam(":end_date", $end_date);
        $stmt->execute();
        return $stmt;
    }

    public function getOverdueTransactions() {
        $query = "SELECT t.*, a.asset_tag, a.name as asset_name, u.name as user_name, u.email as user_email
                  FROM " . $this->table_name . " t
                  JOIN assets a ON t.asset_id = a.id
                  JOIN users u ON t.user_id = u.id
                  WHERE t.action = 'checkout' 
                  AND t.actual_return_date IS NULL 
                  AND t.expected_return_date < NOW()
                  ORDER BY t.expected_return_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
