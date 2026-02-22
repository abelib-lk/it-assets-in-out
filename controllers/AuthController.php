<?php
// controllers/AuthController.php
require_once 'config/database.php';
require_once 'models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!verify_csrf($_POST['csrf_token'])) {
                die("CSRF validation failed");
            }

            $this->user->email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->user->emailExists()) {
                if (password_verify($password, $this->user->password)) {
                    $_SESSION['user_id'] = $this->user->id;
                    $_SESSION['user_name'] = $this->user->name;
                    $_SESSION['role'] = $this->user->role;
                    $_SESSION['last_activity'] = time();
                    
                    redirect('index.php?controller=dashboard&action=index');
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "Email not found.";
            }
        }
        require 'views/auth/login.php';
    }

    public function logout() {
        session_unset();
        session_destroy();
        redirect('index.php?controller=auth&action=login');
    }

    public function forgot_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             if (!verify_csrf($_POST['csrf_token'])) die("CSRF validation failed");
             
             $email = $_POST['email'];
             $this->user->email = $email;
             
             if ($this->user->emailExists()) {
                 $token = bin2hex(random_bytes(32));
                 $this->user->setResetToken($email, $token);
                 
                 // Send Email
                 require_once 'models/Notification.php';
                 $notification = new Notification();
                 $resetLink = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?controller=auth&action=reset_password&token=" . $token;
                 
                 $notification->sendPasswordReset($email, $resetLink);
                 
                 $success = "If your email exists in our system, you will receive a password reset link.";
             } else {
                 // Creating ambiguity for security, or explicit? 
                 // Usually good to be vague, but for internal app specific might be better.
                 // Let's stick to success message to prevent enumeration 
                 // OR for this specific request, explicit might be easier for testing.
                 // Let's use generic success.
                 $success = "If your email exists in our system, you will receive a password reset link.";
             }
        }
        require 'views/auth/forgot_password.php';
    }

    public function reset_password() {
        $token = isset($_GET['token']) ? $_GET['token'] : '';
        if (!$token && isset($_POST['token'])) $token = $_POST['token'];
        
        if (!$token) die("Token missing");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             if (!verify_csrf($_POST['csrf_token'])) die("CSRF validation failed");
             
             $password = $_POST['password'];
             $confirm = $_POST['confirm_password'];
             
             if ($password !== $confirm) {
                 $error = "Passwords do not match.";
             } else {
                 if ($this->user->verifyResetToken($token)) {
                     $password_hash = password_hash($password, PASSWORD_BCRYPT);
                     if ($this->user->updatePassword($this->user->id, $password_hash)) {
                         redirect('index.php?controller=auth&action=login&success=reset');
                     } else {
                         $error = "Failed to update password.";
                     }
                 } else {
                     $error = "Invalid or expired token.";
                 }
             }
        }
        
        // Check if token is valid for GET request (to show form)
        // Optimization: checking again in POST is key.
        
        require 'views/auth/reset_password.php';
    }
}
