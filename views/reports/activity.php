<?php
// views/reports/activity.php
require 'views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Activity Report</h2>
    <div>
        <a href="index.php?controller=report&action=activity&export=csv&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="btn btn-success"><i class="fas fa-file-csv"></i> Export CSV</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-3">
            <input type="hidden" name="controller" value="report">
            <input type="hidden" name="action" value="activity">
            
            <div class="col-md-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Asset Tag</th>
                        <th>Asset Name</th>
                        <th>Related User</th>
                        <th>Processed By</th>
                        <th>Due / Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($activities as $act): ?>
                    <tr>
                        <td><?php echo $act['transaction_date']; ?></td>
                        <td><?php echo ucfirst($act['action']); ?></td>
                        <td><?php echo $act['asset_tag']; ?></td>
                        <td><?php echo $act['asset_name']; ?></td>
                        <td><?php echo $act['user_name']; ?></td>
                        <td><?php echo $act['admin_name']; ?></td>
                        <td>
                            <?php 
                                if ($act['action'] == 'checkout') echo $act['expected_return_date'];
                                if ($act['action'] == 'checkin') echo $act['actual_return_date'];
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
