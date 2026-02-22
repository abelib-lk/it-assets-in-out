<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            background: #fff;
            animation: fadeInUp 0.5s ease-out;
        }
        .login-header {
            background: #fff;
            padding: 40px 40px 20px;
            text-align: center;
        }
        .login-header h3 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #888;
            font-size: 0.9rem;
        }
        .login-body {
            padding: 20px 40px 40px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e1e1e1;
            background-color: #f8f9fc;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
        }
        .btn-login {
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            background: var(--bg-gradient);
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
            color: #fff;
        }
        .input-group-text {
            border: 1px solid #e1e1e1;
            border-right: none;
            background: #f8f9fc;
            border-radius: 8px 0 0 8px;
            color: #888;
        }
        .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            background: #fff;
            color: var(--primary-color);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .brand-icon {
            font-size: 3rem;
            background: -webkit-linear-gradient(#667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-network-wired brand-icon"></i>
            <h3>IT Asset Request and Monitoring System</h3>
            <p>Welcome back! Please login to your account.</p>
        </div>
        
        <div class="login-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div><?php echo $error; ?></div>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['timeout'])): ?>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                     <i class="fas fa-clock me-2"></i>
                     <div>Session timed out. Please login again.</div>
                </div>
            <?php endif; ?>

            <form action="index.php?controller=auth&action=login" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Email Address" required autofocus>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="index.php?controller=auth&action=forgot_password" class="text-decoration-none small">Forgot Password?</a>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-login">
                        Login <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
