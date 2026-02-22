<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $sql1 = "ALTER TABLE transactions MODIFY COLUMN action ENUM('checkout', 'checkin', 'request') NOT NULL";
    $db->exec($sql1);
    echo "Updated 'action' column successfully.\n";

    $sql2 = "ALTER TABLE transactions MODIFY COLUMN admin_id int(11) NULL";
    $db->exec($sql2);
    echo "Updated 'admin_id' column successfully.\n";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
