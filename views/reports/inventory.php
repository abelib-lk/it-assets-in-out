<?php
// views/reports/inventory.php
require 'views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Inventory Report</h2>
    <div>
        <a href="index.php?controller=report&action=inventory&export=csv&category_id=<?php echo $category_id; ?>&status=<?php echo $status; ?>" class="btn btn-success"><i class="fas fa-file-csv"></i> Export CSV</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-3">
            <input type="hidden" name="controller" value="report">
            <input type="hidden" name="action" value="inventory">
            
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo ($category_id == $c['id']) ? 'selected' : ''; ?>><?php echo $c['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="Available" <?php echo ($status == 'Available') ? 'selected' : ''; ?>>Available</option>
                    <option value="Checked Out" <?php echo ($status == 'Checked Out') ? 'selected' : ''; ?>>Checked Out</option>
                    <option value="Under Repair" <?php echo ($status == 'Under Repair') ? 'selected' : ''; ?>>Under Repair</option>
                    <option value="Retired" <?php echo ($status == 'Retired') ? 'selected' : ''; ?>>Retired</option>
                </select>
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
                        <th>Asset Tag</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Model</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Serial No</th>
                        <th>Purchase Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($inventory as $item): ?>
                    <tr>
                        <td><?php echo $item['asset_tag']; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['category_name']; ?></td>
                        <td><?php echo $item['model']; ?></td>
                        <td><?php echo $item['status']; ?></td>
                        <td><?php echo $item['location']; ?></td>
                        <td><?php echo $item['serial_no']; ?></td>
                        <td><?php echo $item['purchase_date']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
