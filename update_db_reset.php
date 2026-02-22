<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "Updating database schema for Password Reset...\n";

try {
    // Check if column exists
    $check = $db->query("SHOW COLUMNS FROM users LIKE 'reset_token'");
    if ($check->rowCount() == 0) {
        $sql = "ALTER TABLE users 
                ADD COLUMN reset_token VARCHAR(64) NULL AFTER role,
                ADD COLUMN reset_expires DATETIME NULL AFTER reset_token";
        $db->exec($sql);
        echo "Columns 'reset_token' and 'reset_expires' added successfully.\n";
    } else {
        echo "Columns already exist.\n";
    }
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
?>
