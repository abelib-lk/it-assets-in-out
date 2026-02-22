<?php
// views/transactions/checkin.php
require 'views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-undo"></i> Check In Asset</h5>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success">Asset checked in successfully. Ready for next scan.</div>
                <?php endif; ?>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <p class="text-muted">Scan the asset tag or enter it manually to check in.</p>

                <form action="index.php?controller=transaction&action=checkin" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    
                    <div class="mb-4">
                        <label class="form-label">Scan Asset Tag</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-qrcode"></i></span>
                            <input type="text" name="asset_tag" id="barcode-scanner" class="form-control form-control-lg" placeholder="Scan or type Asset Tag..." autofocus required>
                            <button type="submit" class="btn btn-primary">Check In</button>
                        </div>
                    </div>
                </form>

                <div class="mt-4">
                    <h6>Recent Check-ins</h6>
                    <!-- Could list recent check-ins here if desired -->
                     <ul class="list-group list-group-flush text-muted small">
                        <li class="list-group-item">Scanning will attempt to find an active loan for the asset.</li>
                        <li class="list-group-item">If found, it will be marked as returned immediately.</li>
                    </ul>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="index.php?controller=transaction&action=index" class="btn btn-link text-decoration-none">Back to Active Loans</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Keep focus on the scanner input
        const scanner = document.getElementById('barcode-scanner');
        scanner.focus();
        
        // Optional: Re-focus on click anywhere (kiosk mode style)
        // document.addEventListener('click', function() { scanner.focus(); });
    });
</script>

<?php require 'views/layouts/footer.php'; ?>
