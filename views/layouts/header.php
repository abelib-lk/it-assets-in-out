<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        .wrapper { display: flex; flex: 1; }
        .sidebar { min-width: 250px; background: #343a40; color: white; min-height: 100vh; }
        .sidebar a { color: rgba(255,255,255,.8); text-decoration: none; padding: 15px 20px; display: block; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar a:hover, .sidebar a.active { background: #495057; color: white; }
        .sidebar i { width: 25px; }
        .content { flex: 1; padding: 20px; background: #f8f9fa; }
        .footer { background: #fff; padding: 15px; text-align: center; border-top: 1px solid #dee2e6; }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><?php echo APP_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user"></i> <?php echo $_SESSION['user_name'] ?? 'User'; ?> (<?php echo ucfirst($_SESSION['role'] ?? ''); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=auth&action=logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="wrapper">
        <div class="sidebar d-none d-md-block">
            <div class="py-4 text-center">
                <h5><i class="fas fa-qrcode"></i> Asset Tracker</h5>
            </div>
            <a href="index.php?controller=dashboard&action=index" class="<?php echo ($controller == 'dashboard') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            
            <?php if($_SESSION['role'] != 'requester'): ?>
            <a href="index.php?controller=asset&action=index" class="<?php echo ($controller == 'asset') ? 'active' : ''; ?>"><i class="fas fa-boxes"></i> Assets</a>
            <a href="index.php?controller=transaction&action=index" class="<?php echo ($controller == 'transaction') ? 'active' : ''; ?>"><i class="fas fa-exchange-alt"></i> Transactions</a>
            <a href="index.php?controller=transaction&action=requests" class="<?php echo ($action == 'requests') ? 'active' : ''; ?>"><i class="fas fa-clock"></i> Pending Requests</a>
            <a href="index.php?controller=report&action=index" class="<?php echo ($controller == 'report') ? 'active' : ''; ?>"><i class="fas fa-chart-bar"></i> Reports</a>
            <?php if($_SESSION['role'] == 'admin'): ?>
            <a href="index.php?controller=user&action=index" class="<?php echo ($controller == 'user') ? 'active' : ''; ?>"><i class="fas fa-users"></i> Users</a>
            <?php endif; ?>
            <?php else: ?>
            <a href="index.php?controller=transaction&action=request" class="<?php echo ($action == 'request') ? 'active' : ''; ?>"><i class="fas fa-hand-holding"></i> Request Asset</a>
            <a href="index.php?controller=transaction&action=my_requests" class="<?php echo ($action == 'my_requests') ? 'active' : ''; ?>"><i class="fas fa-list"></i> My Requests</a>
            <?php endif; ?>
        </div>
        <div class="content">
