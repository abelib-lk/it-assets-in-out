<?php
// views/reports/index.php
require 'views/layouts/header.php';
?>

<div class="mb-4">
    <h2>Reports</h2>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-boxes"></i> Inventory Report</h5>
                <p class="card-text">View current asset inventory with status filters. Export to CSV.</p>
                <a href="index.php?controller=report&action=inventory" class="btn btn-primary">View Inventory</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-exchange-alt"></i> Activity Report</h5>
                <p class="card-text">View transaction history by date range. Export to CSV.</p>
                <a href="index.php?controller=report&action=activity" class="btn btn-primary">View Activity</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-clock"></i> Overdue Report</h5>
                <p class="card-text">View assets that are overdue for return. Export to CSV.</p>
                <a href="index.php?controller=report&action=overdue" class="btn btn-danger">View Overdue Items</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-history"></i> Asset History</h5>
                <p class="card-text">View and export transaction history for specific assets.</p>
                <a href="index.php?controller=asset&action=index" class="btn btn-secondary">Select Asset to View History</a>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
