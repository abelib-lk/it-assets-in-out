<?php
// views/transactions/request.php
require 'views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Request Asset</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="index.php?controller=transaction&action=request" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Select Asset <span class="text-danger">*</span></label>
                        <select name="asset_id" class="form-select select2" required>
                            <option value="">-- Choose Asset --</option>
                            <?php foreach($available_assets as $asset): ?>
                                <option value="<?php echo $asset['id']; ?>">
                                    <?php echo $asset['asset_tag'] . ' - ' . $asset['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expected Return Date <span class="text-danger">*</span></label>
                        <input type="date" name="expected_return_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes (Reason for Request)</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="index.php?controller=dashboard&action=index" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-success">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
