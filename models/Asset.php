<?php
// models/Asset.php
class Asset {
    private $conn;
    private $table_name = "assets";

    public $id;
    public $asset_tag;
    public $name;
    public $category_id;
    public $serial_no;
    public $model;
    public $supplier;
    public $purchase_date;
    public $warranty_expiry;
    public $location;
    public $status;
    public $notes;
    public $qr_code_file;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
        SET asset_tag=:asset_tag, name=:name, category_id=:category_id, serial_no=:serial_no, 
            model=:model, supplier=:supplier, purchase_date=:purchase_date, 
            warranty_expiry=:warranty_expiry, location=:location, status=:status, 
            notes=:notes, qr_code_file=:qr_code_file";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind
        $this->asset_tag = htmlspecialchars(strip_tags($this->asset_tag));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->serial_no = htmlspecialchars(strip_tags($this->serial_no));
        // ... (bind all params)
        
        $stmt->bindParam(":asset_tag", $this->asset_tag);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":serial_no", $this->serial_no);
        $stmt->bindParam(":model", $this->model);
        $stmt->bindParam(":supplier", $this->supplier);
        
        // Handle dates - check for empty
        $p_date = !empty($this->purchase_date) ? $this->purchase_date : null;
        $w_date = !empty($this->warranty_expiry) ? $this->warranty_expiry : null;
        
        $stmt->bindParam(":purchase_date", $p_date);
        $stmt->bindParam(":warranty_expiry", $w_date);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":notes", $this->notes);
        $stmt->bindParam(":qr_code_file", $this->qr_code_file);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getAll() {
        $query = "SELECT a.*, c.name as category_name 
                  FROM " . $this->table_name . " a
                  LEFT JOIN categories c ON a.category_id = c.id
                  ORDER BY a.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById() {
        $query = "SELECT a.*, c.name as category_name 
                  FROM " . $this->table_name . " a
                  LEFT JOIN categories c ON a.category_id = c.id
                  WHERE a.id = ? OR a.asset_tag = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->id); // Allow searching by ID or Tag using same var usually, but here strict.
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->id = $row['id'];
            $this->asset_tag = $row['asset_tag'];
            $this->name = $row['name'];
            $this->category_id = $row['category_id'];
            $this->serial_no = $row['serial_no'];
            $this->model = $row['model'];
            $this->supplier = $row['supplier'];
            $this->purchase_date = $row['purchase_date'];
            $this->warranty_expiry = $row['warranty_expiry'];
            $this->location = $row['location'];
            $this->status = $row['status'];
            $this->notes = $row['notes'];
            $this->qr_code_file = $row['qr_code_file'];
            // Add category name to object if needed, or return row
            return $row;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
        SET name=:name, category_id=:category_id, serial_no=:serial_no, 
            model=:model, supplier=:supplier, purchase_date=:purchase_date, 
            warranty_expiry=:warranty_expiry, location=:location, status=:status, 
            notes=:notes
        WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        
        $p_date = !empty($this->purchase_date) ? $this->purchase_date : null;
        $w_date = !empty($this->warranty_expiry) ? $this->warranty_expiry : null;

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":serial_no", $this->serial_no);
        $stmt->bindParam(":model", $this->model);
        $stmt->bindParam(":supplier", $this->supplier);
        $stmt->bindParam(":purchase_date", $p_date);
        $stmt->bindParam(":warranty_expiry", $w_date);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":notes", $this->notes);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getInventory($category_id = null, $status = null) {
        $query = "SELECT a.*, c.name as category_name 
                  FROM " . $this->table_name . " a
                  LEFT JOIN categories c ON a.category_id = c.id
                  WHERE 1=1";
        
        if ($category_id) {
            $query .= " AND a.category_id = :category_id";
        }
        if ($status) {
            $query .= " AND a.status = :status";
        }
        
        $query .= " ORDER BY a.asset_tag ASC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($category_id) {
            $stmt->bindParam(":category_id", $category_id);
        }
        if ($status) {
            $stmt->bindParam(":status", $status);
        }
        
        $stmt->execute();
        return $stmt;
    }
}
