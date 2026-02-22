<?php
// views/transactions/my_requests.php
require 'views/layouts/header.php';
?>

<h2>My Asset Requests</h2>

<div class="card shadow-sm mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Asset</th>
                        <th>Status</th>
                        <th>Expected Return</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($my_requests as $r): ?>
                        <tr>
                            <td><?php echo date('Y-m-d', strtotime($r['transaction_date'])); ?></td>
                            <td><?php echo $r['asset_tag'] . ' - ' . $r['asset_name']; ?></td>
                            <td>
                                <?php 
                                    if($r['action'] == 'request') echo '<span class="badge bg-warning">Pending</span>';
                                    elseif($r['action'] == 'checkout') echo '<span class="badge bg-success">Approved / Checked Out</span>';
                                    elseif($r['action'] == 'checkin') echo '<span class="badge bg-secondary">Returned</span>';
                                ?>
                            </td>
                            <td><?php echo $r['expected_return_date']; ?></td>
                            <td><?php echo $r['notes']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($my_requests)): ?>
                        <tr><td colspan="5" class="text-center">No requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
