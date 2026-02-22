<?php
// views/reports/overdue.php
require 'views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Overdue Items Report</h2>
    <div>
        <a href="index.php?controller=report&action=overdue&export=csv" class="btn btn-success"><i class="fas fa-file-csv"></i> Export CSV</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> Showing all assets currently checked out past their expected return date.
        </div>
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Expected Return</th>
                        <th>Days Overdue</th>
                        <th>Asset Tag</th>
                        <th>Asset Name</th>
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>Date Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($overdue_items as $item): 
                        $due = new DateTime($item['expected_return_date']);
                        $now = new DateTime();
                        $diff = $now->diff($due);
                        $days = $diff->days;
                    ?>
                    <tr>
                        <td class="text-danger fw-bold"><?php echo $item['expected_return_date']; ?></td>
                        <td><?php echo $days; ?> days</td>
                        <td><?php echo $item['asset_tag']; ?></td>
                        <td><?php echo $item['asset_name']; ?></td>
                        <td><?php echo $item['user_name']; ?></td>
                        <td><a href="mailto:<?php echo $item['user_email']; ?>"><?php echo $item['user_email']; ?></a></td>
                        <td><?php echo $item['transaction_date']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
