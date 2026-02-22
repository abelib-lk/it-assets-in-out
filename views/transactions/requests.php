<?php
// views/transactions/requests.php
require 'views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pending Asset Requests</h2>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?php 
        if($_GET['success'] == 'approved') echo "Request approved successfully.";
        if($_GET['success'] == 'rejected') echo "Request rejected successfully.";
        ?>
    </div>
<?php endif; ?>
<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger">Action failed.</div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Requester</th>
                        <th>Asset Tag</th>
                        <th>Asset Name</th>
                        <th>Expected Return</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($requests as $r): ?>
                        <tr>
                            <td><?php echo date('Y-m-d H:i', strtotime($r['transaction_date'])); ?></td>
                            <td><?php echo $r['user_name']; ?></td>
                            <td><?php echo $r['asset_tag']; ?></td>
                            <td><?php echo $r['asset_name']; ?></td>
                            <td><?php echo $r['expected_return_date']; ?></td>
                            <td><?php echo $r['notes']; ?></td>
                            <td>
                                <a href="index.php?controller=transaction&action=approve&id=<?php echo $r['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve this request and check out the asset?');">Approve</a>
                                <a href="index.php?controller=transaction&action=reject&id=<?php echo $r['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this request?');">Reject</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
