<?php
// controllers/TransactionController.php
require_once 'config/database.php';
require_once 'models/Transaction.php';
require_once 'models/Asset.php';
require_once 'models/User.php';
require_once 'models/Notification.php';

class TransactionController {
    private $db;
    private $transaction;
    private $asset;
    private $user;
    private $notification;

    public function __construct() {
        check_auth();
        $database = new Database();
        $this->db = $database->getConnection();
        $this->transaction = new Transaction($this->db);
        $this->asset = new Asset($this->db);
        $this->user = new User($this->db);
        $this->notification = new Notification();
    }

    public function index() {
        // List active transactions
        $stmt = $this->transaction->getActiveTransactions();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/transactions/index.php';
    }

    public function checkout() {
        require_role(['admin', 'it_staff']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!verify_csrf($_POST['csrf_token'])) die("CSRF Error");

            $asset_id = $_POST['asset_id'];
            $user_id = $_POST['user_id'];
            $expected_return = $_POST['expected_return_date'];
            $notes = $_POST['notes'];

            // 1. Check if asset is available
            $this->asset->id = $asset_id;
            $assetData = $this->asset->getById();
            
            if ($assetData['status'] != 'Available') {
                $error = "Asset is not available for checkout (Status: " . $assetData['status'] . ")";
            } else {
                // 2. Create Transaction
                $this->transaction->asset_id = $asset_id;
                $this->transaction->user_id = $user_id;
                $this->transaction->admin_id = $_SESSION['user_id'];
                $this->transaction->expected_return_date = $expected_return;
                $this->transaction->notes = $notes;

                if ($this->transaction->checkout()) {
                    // 3. Update Asset Status
                    $this->asset->updateStatus($asset_id, 'Checked Out');
                    
                    // 4. Send Notification
                    // Fetch requester details
                    $this->user->id = $user_id;
                    $this->user->getById();
                    $requesterArr = ['name' => $this->user->name, 'email' => $this->user->email];
                    
                    // Admin details
                    $adminArr = ['name' => $_SESSION['user_name']];

                    $this->notification->sendCheckoutNotification($assetData, $requesterArr, $adminArr, $expected_return);

                    redirect('index.php?controller=transaction&action=index&success=checkout');
                } else {
                    $error = "Failed to create transaction.";
                }
            }
        }
        
        // Load data for form
        // Get available assets
        $stmtAP = $this->db->query("SELECT * FROM assets WHERE status='Available' ORDER BY asset_tag");
        $available_assets = $stmtAP->fetchAll(PDO::FETCH_ASSOC);

        // Get users
        $stmtU = $this->db->query("SELECT * FROM users ORDER BY name");
        $users = $stmtU->fetchAll(PDO::FETCH_ASSOC);

        // Pre-select asset if passed in URL
        $selected_asset = isset($_GET['asset_id']) ? $_GET['asset_id'] : '';

        require 'views/transactions/checkout.php';
    }

    public function checkin() {
        require_role(['admin', 'it_staff']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!verify_csrf($_POST['csrf_token'])) die("CSRF Error");
            
            $transaction_id = $_POST['transaction_id'] ?? null;
            $asset_id = $_POST['asset_id'] ?? null;
            $asset_tag = $_POST['asset_tag'] ?? null;

            // Handle Scan/Input of Asset Tag
            if ($asset_tag && !$transaction_id) {
                $trans = $this->transaction->getActiveTransactionByAssetTag($asset_tag);
                if ($trans) {
                    $transaction_id = $trans['id'];
                    $asset_id = $trans['asset_id'];
                } else {
                    $error = "No active checkout found for Asset Tag: " . htmlspecialchars($asset_tag);
                    // Fallthrough to render view with error
                }
            }
            
            if ($transaction_id && $asset_id) {
                 // 1. Update Transaction
                if ($this->transaction->checkin($transaction_id)) {
                    // 2. Update Asset Status
                    $this->asset->updateStatus($asset_id, 'Available');
                    
                    // 3. Send Notification (Optional but good)
                    redirect('index.php?controller=transaction&action=checkin&success=checkin');
                } else {
                    $error = "Failed to check in.";
                }
            }
        }
        
        // Load Check-in View (for GET or failed POST)
        require 'views/transactions/checkin.php';
    }
    public function request() {
        // For Requesters
        if ($_SESSION['role'] !== 'requester') {
             // Access control: only users can request? Admin/IT can too potentially but usually they checkout.
             // We'll allow any authenticated user to request if we want, but typically it's for 'requester' role.
             // Let's allow everyone for flexibility, or restrict if needed.
             // check_auth() is already called in constructor.
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             if (!verify_csrf($_POST['csrf_token'])) die("CSRF Error");

             $asset_id = $_POST['asset_id'];
             $user_id = $_SESSION['user_id']; // Current user
             $expected_return = $_POST['expected_return_date'];
             $notes = $_POST['notes'];
 
             // 1. Check if asset is available
             $this->asset->id = $asset_id;
             $assetData = $this->asset->getById();
             
             if ($assetData['status'] != 'Available') {
                 $error = "Asset is not available for request.";
             } else {
                 $this->transaction->asset_id = $asset_id;
                 $this->transaction->user_id = $user_id;
                 $this->transaction->expected_return_date = $expected_return;
                 $this->transaction->notes = $notes;
 
                 if ($this->transaction->request()) {
                     // Notify IT Staff (Optional - TODO)
                     redirect('index.php?controller=transaction&action=my_requests&success=requested');
                 } else {
                     $error = "Failed to submit request.";
                 }
             }
        }

        // Load View
        // Get available assets
        $stmtAP = $this->db->query("SELECT * FROM assets WHERE status='Available' ORDER BY asset_tag");
        $available_assets = $stmtAP->fetchAll(PDO::FETCH_ASSOC);
        
        require 'views/transactions/request.php';
    }

    public function my_requests() {
         $user_id = $_SESSION['user_id'];
         $stmt = $this->transaction->getUserRequests($user_id);
         $my_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
         
         require 'views/transactions/my_requests.php';
    }

    public function requests() {
        require_role(['admin', 'it_staff']);
        
        $stmt = $this->transaction->getPendingRequests();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/transactions/requests.php';
    }

    public function approve() {
        require_role(['admin', 'it_staff']);
        
        if (!isset($_GET['id'])) redirect('index.php?controller=transaction&action=requests');
        $transaction_id = $_GET['id'];
        
        // We need to fetch the transaction to get asset_id to update status
         // A bit of a shortcut here, ideally we fetch transaction details first.
         // Let's assumed passed via GET or we fetch it.
         // For safety, let's fetch it.
         $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ?");
         $stmt->execute([$transaction_id]);
         $trans = $stmt->fetch(PDO::FETCH_ASSOC);
         
         if ($trans && $trans['action'] == 'request') {
             if ($this->transaction->approve($transaction_id, $_SESSION['user_id'])) {
                 $this->asset->updateStatus($trans['asset_id'], 'Checked Out');
                 
                 // Send Notification
                 // Fetch requester details
                 $this->user->id = $trans['user_id'];
                 $this->user->getById();
                 $requesterArr = ['name' => $this->user->name, 'email' => $this->user->email];
                 
                 // Admin details
                 $adminArr = ['name' => $_SESSION['user_name']];

                 // Fetch asset details
                 $this->asset->id = $trans['asset_id'];
                 $assetData = $this->asset->getById();

                 $this->notification->sendCheckoutNotification($assetData, $requesterArr, $adminArr, $trans['expected_return_date']);

                 redirect('index.php?controller=transaction&action=requests&success=approved');
             }
         }
         redirect('index.php?controller=transaction&action=requests&error=failed');
    }

    public function reject() {
        require_role(['admin', 'it_staff']);
        if (!isset($_GET['id'])) redirect('index.php?controller=transaction&action=requests');
        $transaction_id = $_GET['id'];
        
        $this->transaction->reject($transaction_id);
        redirect('index.php?controller=transaction&action=requests&success=rejected');
    }
}
