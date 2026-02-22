<?php
// index.php
require_once 'config/app.php';


// Simple Router
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Check auth for non-auth controllers
if ($controller !== 'auth' && !isset($_SESSION['user_id'])) {
    redirect('index.php?controller=auth&action=login');
}

// Route mapping
switch ($controller) {
    case 'auth':
        require_once 'controllers/AuthController.php';
        $ctrl = new AuthController();
        break;
    case 'dashboard':
        require_once 'controllers/ReportController.php'; // Reuse report controller for dashboard or simple view
        // Ideally DashboardController
        require_once 'controllers/DashboardController.php';
        $ctrl = new DashboardController();
        break;
    case 'asset':
        require_once 'controllers/AssetController.php';
        $ctrl = new AssetController();
        break;
    case 'transaction':
        require_once 'controllers/TransactionController.php';
        $ctrl = new TransactionController();
        break;
    case 'user':
        require_once 'controllers/UserController.php';
        $ctrl = new UserController();
        break;
    case 'report':
        require_once 'controllers/ReportController.php';
        $ctrl = new ReportController();
        break;
    default:
        die("Controller not found.");
}

if (method_exists($ctrl, $action)) {
    $ctrl->$action();
} else {
    die("Action not found.");
}
