<?php
// views/assets/view.php
require 'views/layouts/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Asset Details: <?php echo $asset['asset_tag']; ?></h5>
                <span class="badge bg-<?php 
                    echo match($asset['status']) {
                        'Available' => 'success',
                        'Checked Out' => 'warning',
                        'Under Repair' => 'danger',
                        'Retired' => 'dark',
                        default => 'secondary'
                    };
                ?> fs-6"><?php echo $asset['status']; ?></span>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Name</th>
                        <td><?php echo $asset['name']; ?></td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td><?php echo $asset['category_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Model</th>
                        <td><?php echo $asset['model']; ?></td>
                    </tr>
                    <tr>
                        <th>Serial No</th>
                        <td><?php echo $asset['serial_no']; ?></td>
                    </tr>
                    <tr>
                        <th>Purchase Date</th>
                        <td><?php echo $asset['purchase_date']; ?></td>
                    </tr>
                    <tr>
                        <th>Warranty Expiry</th>
                        <td><?php echo $asset['warranty_expiry']; ?></td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td><?php echo $asset['location']; ?></td>
                    </tr>
                    <tr>
                        <th>Notes</th>
                        <td><?php echo nl2br($asset['notes']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">QR Code</h6>
            </div>
            <div class="card-body text-center">
                <?php if($asset['qr_code_file'] && file_exists(QR_CODE_PATH . $asset['qr_code_file'])): ?>
                    <img src="<?php echo ASSET_URL . '../uploads/qrcodes/' . $asset['qr_code_file']; ?>" class="img-fluid" style="max-width: 200px;">
                    <div class="mt-2">
                        <a href="<?php echo ASSET_URL . '../uploads/qrcodes/' . $asset['qr_code_file']; ?>" download="<?php echo $asset['asset_tag']; ?>.png" class="btn btn-sm btn-outline-secondary"><i class="fas fa-download"></i> Download</a>
                        <a href="#" onclick="window.open('index.php?controller=asset&action=print_label&id=<?php echo $asset['id']; ?>', 'print_label', 'width=600,height=400'); return false;" class="btn btn-sm btn-outline-primary"><i class="fas fa-print"></i> Print Label</a>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No QR Code generated.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">Transaction History</h6>
            </div>
            <div class="card-body p-0">
                <!-- Fetch transaction history via AJAX or separate controller call if needed, for now placeholder linkage -->
                <div class="p-3 text-center">
                    <a href="index.php?controller=report&action=history&id=<?php echo $asset['id']; ?>" class="btn btn-outline-primary btn-sm block">View Full History</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
