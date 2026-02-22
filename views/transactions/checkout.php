<?php
// views/transactions/checkout.php
require 'views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Check Out Asset</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="index.php?controller=transaction&action=checkout" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Scan Asset Tag</label>
                        <input type="text" id="barcode-scanner" class="form-control" placeholder="Click here and scan barcode..." autofocus>
                        <small class="text-muted">Scanning a tag will automatically select the asset below.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Asset <span class="text-danger">*</span></label>
                        <select name="asset_id" id="asset_select" class="form-select select2" required>
                            <option value="">-- Choose Asset --</option>
                            <?php foreach($available_assets as $asset): ?>
                                <option value="<?php echo $asset['id']; ?>" data-tag="<?php echo $asset['asset_tag']; ?>" <?php echo ($selected_asset == $asset['id']) ? 'selected' : ''; ?>>
                                    <?php echo $asset['asset_tag'] . ' - ' . $asset['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const scanner = document.getElementById('barcode-scanner');
                            const assetSelect = $('#asset_select'); // Use jQuery for Select2

                            scanner.addEventListener('keypress', function(e) {
                                if (e.key === 'Enter') {
                                    e.preventDefault();
                                    const code = this.value.trim();
                                    if(code) {
                                        // Find option with this data-tag
                                        // Since we don't have data-tag in the DOM properly for Select2 to search easily without mapping, 
                                        // we iterate options.
                                        let foundId = null;
                                        $('#asset_select option').each(function() {
                                            // Check text content or add data attribute
                                            // We added data-tag above.
                                            if ($(this).data('tag') === code || $(this).text().includes(code)) {
                                                foundId = $(this).val();
                                                return false; // break
                                            }
                                        });

                                        if(foundId) {
                                            assetSelect.val(foundId).trigger('change');
                                            // Optional: Focus user select next
                                            // $('#user_select').select2('open'); 
                                            this.value = ''; // Clear
                                            // Play success sound?
                                        } else {
                                            alert('Asset not found or not available: ' + code);
                                            this.value = '';
                                        }
                                    }
                                }
                            });
                        });
                    </script>

                    <div class="mb-3">
                        <label class="form-label">Requester (Employee) <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select select2" required>
                            <option value="">-- Choose User --</option>
                            <?php foreach($users as $u): ?>
                                <option value="<?php echo $u['id']; ?>"><?php echo $u['name'] . ' (' . $u['email'] . ')'; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expected Return Date <span class="text-danger">*</span></label>
                        <input type="date" name="expected_return_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="index.php?controller=transaction&action=index" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Process Checkout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
