<?php
// controllers/UserController.php
require_once 'config/database.php';
require_once 'models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        require_role(['admin']);
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function index() {
        $stmt = $this->user->getAll();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/users/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!verify_csrf($_POST['csrf_token'])) die("CSRF Error");

            $this->user->name = $_POST['name'];
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password']; // Will be hashed in model
            $this->user->role = $_POST['role'];

            if ($this->user->create()) {
                redirect('index.php?controller=user&action=index&success=created');
            } else {
                $error = "Failed to create user. Email may be duplicate.";
            }
        }
        require 'views/users/create.php';
    }

    // Add edit/delete if needed
}
