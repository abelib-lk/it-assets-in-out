<?php
// controllers/ReportController.php
require_once 'config/database.php';
require_once 'models/Asset.php';
require_once 'models/Transaction.php';
require_once 'vendor/autoload.php';

class ReportController {
    private $db;
    private $asset;
    private $transaction;

    public function __construct() {
        check_auth();
        $database = new Database();
        $this->db = $database->getConnection();
        $this->asset = new Asset($this->db);
        $this->transaction = new Transaction($this->db);
    }

    public function index() {
        // Just show links to available reports
        require 'views/reports/index.php';
    }

    public function history() {
         if (!isset($_GET['id'])) {
             // Show selector or redirect
             redirect('index.php?controller=asset&action=index');
         }
         
         $asset_id = $_GET['id'];
         $this->asset->id = $asset_id;
         $assetData = $this->asset->getById();
         
         $stmt = $this->transaction->getHistoryByAsset($asset_id);
         $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

         if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
             $this->exportHistoryPdf($assetData, $history);
         } else {
             require 'views/reports/history.php';
         }
    }

    private function exportHistoryPdf($asset, $history) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Asset History - ' . $asset['asset_tag']);
        $pdf->SetHeaderData('', 0, 'IT Asset Tracker', 'Asset History Report');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 25);
        $pdf->AddPage();

        $html = '<h1>Asset History Report</h1>';
        $html .= '<h3>Asset: ' . $asset['name'] . ' (' . $asset['asset_tag'] . ')</h3>';
        $html .= '<p><strong>Model:</strong> ' . $asset['model'] . '<br><strong>Current Status:</strong> ' . $asset['status'] . '</p>';
        
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr style="background-color:#eee;">
                    <th>Date</th>
                    <th>Action</th>
                    <th>User</th>
                    <th>Processed By</th>
                    <th>Notes</th>
                  </tr>';
        
        foreach ($history as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $row['transaction_date'] . '</td>';
            $html .= '<td>' . ucfirst($row['action']) . '</td>';
            $html .= '<td>' . $row['user_name'] . '</td>';
            $html .= '<td>' . $row['admin_name'] . '</td>';
            $html .= '<td>' . $row['notes'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('asset_history_' . $asset['asset_tag'] . '.pdf', 'D');
        exit;
    }
    public function inventory() {
        require_role(['admin', 'it_staff']);
        require_once 'models/Category.php';
        $category = new Category($this->db);
        $categories = $category->getAll()->fetchAll(PDO::FETCH_ASSOC);

        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;

        $stmt = $this->asset->getInventory($category_id, $status);
        $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($_GET['export']) && $_GET['export'] == 'csv') {
            $this->exportCsv($inventory, 'inventory_report_' . date('Y-m-d'));
        }

        require 'views/reports/inventory.php';
    }

    public function activity() {
        require_role(['admin', 'it_staff']);
        
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $stmt = $this->transaction->getTransactionsByDateRange($start_date . ' 00:00:00', $end_date . ' 23:59:59');
        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($_GET['export']) && $_GET['export'] == 'csv') {
            $this->exportCsv($activities, 'activity_report_' . $start_date . '_to_' . $end_date);
        }

        require 'views/reports/activity.php';
    }

    public function overdue() {
        require_role(['admin', 'it_staff']);
        
        $stmt = $this->transaction->getOverdueTransactions();
        $overdue_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($_GET['export']) && $_GET['export'] == 'csv') {
            $this->exportCsv($overdue_items, 'overdue_report_' . date('Y-m-d'));
        }

        require 'views/reports/overdue.php';
    }

    private function exportCsv($data, $filename) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename . '.csv');
        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            // Add BOM for Excel UTF-8 compatibility
            fputs($output, "\xEF\xBB\xBF");
            
            // Get headers from first row keys
            fputcsv($output, array_keys($data[0]));
            
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        fclose($output);
        exit;
    }
}
