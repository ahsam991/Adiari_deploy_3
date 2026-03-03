<?php
/**
 * Shop Module Layout - Header
 * Integrated into admin dashboard MVC framework
 */
$APP_NAME = $APP_NAME ?? 'ADI ARI FRESH VEGETABLE AND HALAL FOOD';
$pageTitle = $pageTitle ?? 'Shop';
$role = $shop_user['role'] ?? 'staff';
$isLoggedIn = isset($shop_user['id']) && $shop_user['id'] !== null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($APP_NAME); ?> - <?php echo htmlspecialchars($pageTitle ?? 'Shop'); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Shop Custom CSS -->
    <link href="<?php echo Router::url('/css/shop-style.css'); ?>" rel="stylesheet">
    
    <!-- QR Code / Barcode Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        .scanner-container { background: #000; border-radius: 8px; overflow: hidden; }
        #reader__dashboard_section_csr button { border-radius: 4px; border: 1px solid #ccc; padding: 4px 8px; margin: 4px; }
    </style>
</head>
<body class="shop-app">
    
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo Router::url('/shop/dashboard'); ?>">
                <i class="bi bi-shop"></i> <?php echo htmlspecialchars($APP_NAME); ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#shopNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="shopNavbar">
                <?php if ($isLoggedIn): ?>
                <ul class="navbar-nav ms-auto">
                    <?php if ($role !== 'staff'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/shop/dashboard') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo Router::url('/shop/dashboard'); ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/shop/inventory') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo Router::url('/shop/inventory'); ?>">
                            <i class="bi bi-box-seam"></i> Inventory
                        </a>
                    </li>
                    <?php if ($role !== 'staff'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/shop/sales') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo Router::url('/shop/sales'); ?>">
                            <i class="bi bi-cart-check"></i> Sales
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/shop/pos') !== false && strpos($_SERVER['REQUEST_URI'], '/shop/pos/invoice') === false) ? 'active' : ''; ?>" 
                           href="<?php echo Router::url('/shop/pos'); ?>">
                            <i class="bi bi-cash-register"></i> POS
                        </a>
                    </li>
                    <?php if (in_array($role, ['admin', 'manager'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/shop/customers') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo Router::url('/shop/customers'); ?>">
                            <i class="bi bi-people"></i> Customers
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (in_array($role, ['admin', 'manager'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/shop/coupons') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo Router::url('/shop/coupons'); ?>">
                            <i class="bi bi-tag"></i> Coupons
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <!-- User Info, Admin Dashboard Link & Logout -->
                <ul class="navbar-nav ms-3">
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="<?php echo Router::url('/'); ?>" title="Back to Main Site">
                            <i class="bi bi-house-door"></i> Main Site
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="shopUserDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> 
                            <?php echo htmlspecialchars($shop_user['full_name'] ?? 'User'); ?>
                            <span class="badge bg-light text-primary ms-1"><?php echo strtoupper($role); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($role === 'admin'): ?>
                            <li><a class="dropdown-item" href="<?php echo Router::url('/admin'); ?>"><i class="bi bi-shield-lock"></i> Admin Panel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger" href="<?php echo Router::url('/shop/logout'); ?>">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Main Content Container -->
    <div class="container-fluid mt-4">
        
        <!-- Display Flash Messages -->
        <?php if (!empty($shop_success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($shop_success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($shop_error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($shop_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($shop_errors) && is_array($shop_errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($shop_errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
