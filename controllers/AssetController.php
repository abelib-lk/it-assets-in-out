<?php
// controllers/AssetController.php
require_once 'config/database.php';
require_once 'models/Asset.php';
require_once 'models/Category.php';
require_once 'vendor/autoload.php';

class AssetController {
    private $db;
    private $asset;
    private $category;

    public function __construct() {
        check_auth();
        $database = new Database();
        $this->db = $database->getConnection();
        $this->asset = new Asset($this->db);
        $this->category = new Category($this->db);
    }

    public function index() {
        $stmt = $this->asset->getAll();
        $assets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/assets/index.php';
    }

    public function create() {
        require_role(['admin', 'it_staff']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!verify_csrf($_POST['csrf_token'])) die("CSRF Error");

            $this->asset->asset_tag = $_POST['asset_tag'];
            $this->asset->name = $_POST['name'];
            $this->asset->category_id = $_POST['category_id'];
            $this->asset->serial_no = $_POST['serial_no'];
            $this->asset->model = $_POST['model'];
            $this->asset->supplier = $_POST['supplier'];
            $this->asset->purchase_date = $_POST['purchase_date'];
            $this->asset->warranty_expiry = $_POST['warranty_expiry'];
            $this->asset->location = $_POST['location'];
            $this->asset->status = 'Available'; // Default
            $this->asset->notes = $_POST['notes'];

            // Generate QR Code
            $qr_data = $this->asset->asset_tag;
            $qr_file = $this->asset->asset_tag . '.png';
            $qr_path = QR_CODE_PATH . $qr_file;
            
            // Check directory
            if (!file_exists(QR_CODE_PATH)) {
                mkdir(QR_CODE_PATH, 0777, true);
            }

            // TCPDF Barcode
            $barcodeobj = new TCPDF2DBarcode($qr_data, 'QRCODE,H');
            file_put_contents($qr_path, $barcodeobj->getBarcodePngData(10, 10)); // 10x10 scale

            $this->asset->qr_code_file = $qr_file;

            if ($this->asset->create()) {
                redirect('index.php?controller=asset&action=index&success=created');
            } else {
                $error = "Failed to create asset. Asset Tag might be duplicate.";
            }
        }
        
        $categories = $this->category->getAll()->fetchAll(PDO::FETCH_ASSOC);
        require 'views/assets/create.php';
    }

    public function view() {
        if (!isset($_GET['id'])) redirect('index.php?controller=asset&action=index');
        $this->asset->id = $_GET['id'];
        $asset = $this->asset->getById();
        if (!$asset) die("Asset not found");
        
        require 'views/assets/view.php';
    }

    public function print_label() {
        if (!isset($_GET['id'])) die("ID required");
        require_role(['admin', 'it_staff']);
        
        $this->asset->id = $_GET['id'];
        $asset = $this->asset->getById();
        if (!$asset) die("Asset not found");
        
        require 'views/assets/print_label.php';
    }

    // Additional methods: edit, delete...
}
