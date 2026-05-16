<?php
/** @var string $pageTitle */
/** @var string $activeNav dashboard|products|orders */
/** @var string $portalLabel */
if (!isset($pageTitle)) {
    $pageTitle = 'Aura Essence | Admin';
}
if (!isset($activeNav)) {
    $activeNav = 'dashboard';
}
if (!isset($portalLabel)) {
    $portalLabel = 'Aura & Essence — Management Portal';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <style>
        .app-sidebar { background: #121418 !important; }
        .brand-link .brand-text { font-family: Georgia, 'Playfair Display', serif; letter-spacing: 0.5px; }
        .brand-link strong { color: #f0f6fc; }
    </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

<div class="app-wrapper">

    <nav class="app-header navbar navbar-expand bg-body shadow-sm">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
            </ul>
            <span class="navbar-text fw-bold text-uppercase ms-2" style="font-size:12px; letter-spacing:1.5px; color:#6b7280;">
                <?php echo htmlspecialchars($portalLabel); ?>
            </span>
        </div>
    </nav>

    <aside class="app-sidebar shadow" data-bs-theme="dark">
        <div class="sidebar-brand border-bottom border-secondary py-3">
            <a href="index.php" class="brand-link text-center text-decoration-none">
                <img src="dist/assets/img/AdminLTELogo.png" alt="Aura Essence" class="brand-image opacity-75 shadow mb-2">
                <span class="brand-text fw-light d-block">
                    <strong>Aura</strong> & Essence
                </span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link<?php echo $activeNav === 'dashboard' ? ' active' : ''; ?>">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="manage_products.php" class="nav-link<?php echo $activeNav === 'products' ? ' active' : ''; ?>">
                            <i class="nav-icon bi bi-droplet"></i>
                            <p>Manage Perfumes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="manage_orders.php" class="nav-link<?php echo $activeNav === 'orders' ? ' active' : ''; ?>">
                            <i class="nav-icon bi bi-bag"></i>
                            <p>Orders Panel</p>
                        </a>
                    </li>
                    <li class="nav-header border-top border-secondary my-2">STORE</li>
                    <li class="nav-item">
                        <a href="../index.php" class="nav-link" target="_blank" rel="noopener">
                            <i class="nav-icon bi bi-globe"></i>
                            <p>View Live Store</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
