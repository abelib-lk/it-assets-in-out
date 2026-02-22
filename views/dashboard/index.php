<?php
// views/dashboard/index.php
require 'views/layouts/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Dashboard</h2>
        <p class="text-muted">Welcome, <?php echo $_SESSION['user_name']; ?></p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Assets</h6>
                        <h2 class="my-2"><?php echo $stats['total_assets']; ?></h2>
                    </div>
                    <i class="fas fa-boxes fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Checked Out</h6>
                        <h2 class="my-2"><?php echo $stats['checked_out']; ?></h2>
                    </div>
                    <i class="fas fa-hand-holding fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Overdue</h6>
                        <h2 class="my-2"><?php echo $stats['overdue']; ?></h2>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Categories</h6>
                        <h2 class="my-2"><?php echo count($stats['categories']); ?></h2>
                    </div>
                    <i class="fas fa-tags fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                 <h5 class="mb-0">Assets by Category</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php foreach($stats['categories'] as $cat): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo $cat['name']; ?>
                        <span class="badge bg-primary rounded-pill"><?php echo $cat['count']; ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                 <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="index.php?controller=transaction&action=checkout" class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i> Check Out Asset</a>
                    <a href="index.php?controller=transaction&action=checkin" class="btn btn-outline-success"><i class="fas fa-undo"></i> Check In Asset</a>
                    <a href="index.php?controller=asset&action=create" class="btn btn-outline-secondary"><i class="fas fa-laptop-medical"></i> Add New Asset</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
