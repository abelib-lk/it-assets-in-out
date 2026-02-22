<?php
// views/assets/index.php
require 'views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Asset Inventory</h2>
    <?php if($_SESSION['role'] != 'requester'): ?>
    <a href="index.php?controller=asset&action=create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Asset</a>
    <?php endif; ?>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">Operation successful!</div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Tag</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Model</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($assets as $asset): ?>
                    <tr>
                        <td><?php echo $asset['asset_tag']; ?></td>
                        <td>
                            <a href="index.php?controller=asset&action=view&id=<?php echo $asset['id']; ?>">
                                <?php echo $asset['name']; ?>
                            </a>
                        </td>
                        <td><?php echo $asset['category_name']; ?></td>
                        <td><?php echo $asset['model']; ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo match($asset['status']) {
                                    'Available' => 'success',
                                    'Checked Out' => 'warning',
                                    'Under Repair' => 'danger',
                                    'Retired' => 'dark',
                                    default => 'secondary'
                                };
                            ?>">
                                <?php echo $asset['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $asset['location']; ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="index.php?controller=asset&action=view&id=<?php echo $asset['id']; ?>" class="btn btn-info text-white"><i class="fas fa-eye"></i></a>
                                <?php if($_SESSION['role'] != 'requester'): ?>
                                <a href="index.php?controller=asset&action=edit&id=<?php echo $asset['id']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
