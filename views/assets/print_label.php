<?php
// views/assets/print_label.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Asset Label - <?php echo $asset['asset_tag']; ?></title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 20px; }
        .label-container { border: 1px dashed #ccc; display: inline-block; padding: 20px; margin-bottom: 20px; }
        .asset-tag { font-size: 24px; font-weight: bold; margin-top: 10px; }
        .asset-name { font-size: 16px; color: #555; }
        @media print {
            .no-print { display: none; }
            .label-container { border: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="label-container">
        <?php if($asset['qr_code_file'] && file_exists(QR_CODE_PATH . $asset['qr_code_file'])): ?>
            <img src="<?php echo ASSET_URL . '../uploads/qrcodes/' . $asset['qr_code_file']; ?>" style="width: 150px;">
        <?php else: ?>
            <p>No QR Code</p>
        <?php endif; ?>
        <div class="asset-tag"><?php echo $asset['asset_tag']; ?></div>
        <div class="asset-name"><?php echo $asset['name']; ?></div>
    </div>
    
    <div class="no-print">
        <button onclick="window.print()">Print Again</button>
        <button onclick="window.close()">Close</button>
    </div>
</body>
</html>
