<?php
// views/reports/history.php
require 'views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Asset History</h2>
        <p class="text-muted"><?php echo $assetData['name'] . ' (' . $assetData['asset_tag'] . ')'; ?></p>
    </div>
    <div>
        <a href="index.php?controller=report&action=history&id=<?php echo $assetData['id']; ?>&export=pdf" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Export PDF</a>
        <a href="index.php?controller=asset&action=view&id=<?php echo $assetData['id']; ?>" class="btn btn-secondary">Back to Asset</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                    <th>User</th>
                    <th>Processed By</th>
                    <th>Return Date (Actual)</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($history as $t): ?>
                <tr>
                    <td><?php echo $t['transaction_date']; ?></td>
                    <td>
                        <span class="badge bg-<?php echo ($t['action']=='checkout') ? 'warning' : 'success'; ?>">
                            <?php echo ucfirst($t['action']); ?>
                        </span>
                    </td>
                    <td><?php echo $t['user_name']; ?></td>
                    <td><?php echo $t['admin_name']; ?></td>
                    <td><?php echo $t['actual_return_date'] ? $t['actual_return_date'] : '-'; ?></td>
                    <td><?php echo $t['notes']; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($history)): ?>
                <tr>
                    <td colspan="6" class="text-center">No history found for this asset.</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
