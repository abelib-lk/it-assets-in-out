<?php
// views/transactions/index.php
require 'views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Active Loans</h2>
    <div>
        <a href="index.php?controller=transaction&action=checkin" class="btn btn-success me-2"><i class="fas fa-undo"></i> Check In (Scan)</a>
        <a href="index.php?controller=transaction&action=checkout" class="btn btn-primary"><i class="fas fa-plus"></i> New Check-out</a>
    </div>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">Transaction processed successfully!</div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Asset Tag</th>
                    <th>Asset Name</th>
                    <th>Requester</th>
                    <th>Date Out</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transactions as $t): 
                    $dueDate = new DateTime($t['expected_return_date']);
                    $now = new DateTime();
                    $isOverdue = $now > $dueDate;
                ?>
                <tr class="<?php echo $isOverdue ? 'table-danger' : ''; ?>">
                    <td><?php echo $t['asset_tag']; ?></td>
                    <td><?php echo $t['asset_name']; ?></td>
                    <td><?php echo $t['user_name']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($t['transaction_date'])); ?></td>
                    <td>
                        <?php echo date('Y-m-d', strtotime($t['expected_return_date'])); ?>
                        <?php if($isOverdue): ?>
                            <span class="badge bg-danger">Overdue</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="index.php?controller=transaction&action=checkin" method="POST" onsubmit="return confirm('Are you sure you want to check in this asset?');">
                            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                            <input type="hidden" name="transaction_id" value="<?php echo $t['id']; ?>">
                            <input type="hidden" name="asset_id" value="<?php echo $t['asset_id']; ?>">
                            <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-undo"></i> Check In</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
