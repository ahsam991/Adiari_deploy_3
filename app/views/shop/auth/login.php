<?php
/**
 * Shop Login Page (standalone - no header/footer layout)
 */
$APP_NAME = $APP_NAME ?? 'ADI ARI FRESH VEGETABLE AND HALAL FOOD';
// This view uses its own layout (no header/footer includes needed by ShopController 
// since login has its own full HTML). We override the renderShopView behavior.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo htmlspecialchars($APP_NAME); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #14b8a6 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 920px;
            width: 100%;
            display: flex;
        }
        .login-left {
            flex: 1;
            background: linear-gradient(160deg, #0f766e 0%, #0d9488 50%, #059669 100%);
            padding: 60px 45px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-left h1 { font-size: 2.25rem; font-weight: 800; margin-bottom: 16px; }
        .login-left p { font-size: 1rem; opacity: 0.95; line-height: 1.7; margin-bottom: 2rem; }
        .login-left .feature { display: flex; align-items: center; margin: 12px 0; font-weight: 600; }
        .login-left .feature i { font-size: 1.25rem; margin-right: 14px; opacity: 0.9; }
        .login-right { flex: 1; padding: 60px 45px; }
        .login-right h2 { color: #1f2937; margin-bottom: 8px; font-weight: 800; font-size: 1.75rem; }
        .login-right > p { color: #6b7280; margin-bottom: 28px; }
        .form-control { border-radius: 12px; padding: 12px 16px; border: 2px solid #e5e7eb; transition: all 0.2s; }
        .form-control:focus { border-color: #0d9488; box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15); }
        .input-group-text { border-radius: 12px 0 0 12px; border: 2px solid #e5e7eb; border-right: none; background: #f9fafb; }
        .input-group .form-control { border-left: none; border-radius: 0 12px 12px 0; }
        .btn-login { background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); border: none; border-radius: 12px; padding: 14px; font-weight: 700; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(13, 148, 136, 0.4); }
        .alert { border-radius: 12px; border: none; }
        .default-credentials { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px; margin-top: 24px; font-size: 0.9rem; }
        .default-credentials strong { color: #0d9488; }
        @media (max-width: 768px) { .login-container { flex-direction: column; } .login-left, .login-right { padding: 40px 30px; } }
    </style>
</head>
<body class="shop-app">
    <div class="login-container">
        <div class="login-left">
            <h1><i class="bi bi-shop"></i> <?php echo htmlspecialchars($APP_NAME); ?></h1>
            <p>Complete inventory and point of sale solution for your grocery business</p>
            <div class="feature"><i class="bi bi-box-seam"></i><span>Inventory Management</span></div>
            <div class="feature"><i class="bi bi-cash-register"></i><span>Point of Sale System</span></div>
            <div class="feature"><i class="bi bi-graph-up"></i><span>Sales Analytics</span></div>
            <div class="feature"><i class="bi bi-people"></i><span>Multi-User Support</span></div>
        </div>
        <div class="login-right">
            <h2>Welcome Back!</h2>
            <p>Please login to your POS account</p>
            <?php if (!empty($shop_error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i>
                    <?php echo htmlspecialchars($shop_error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (!empty($shop_success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i>
                    <?php echo htmlspecialchars($shop_success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo Router::url('/shop/login'); ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-login w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>
            <div class="text-center mt-3">
                <a href="<?php echo Router::url('/'); ?>" class="text-muted"><i class="bi bi-arrow-left"></i> Back to Main Site</a>
            </div>
            <div class="default-credentials">
                <strong><i class="bi bi-info-circle"></i> Default Credentials:</strong><br>
                <small><strong>Admin:</strong> admin / admin123<br>
                <em class="text-danger">⚠️ Change password after first login!</em></small>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
