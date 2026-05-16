<?php include('../config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Essence | Manage Orders</title>
    <link class="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    
    <div class="app-wrapper">
        
        <nav class="app-header navbar navbar-expand bg-body shadow-sm">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#"><i class="fas fa-bars"></i></a></li>
                </ul>
                <span class="navbar-text fw-bold text-uppercase ms-2">Aura & Essence — Orders Portal</span>
            </div>
        </nav>

        <aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
            <div class="sidebar-brand border-bottom border-secondary py-3">
                <a href="index.php" class="brand-link text-center text-decoration-none">
                    <span class="brand-text fw-light text-light" style="letter-spacing: 2px;"><strong>AURA</strong> ESSENCE</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-3">
                    <ul class="nav sidebar-menu flex-column" role="menu">
                        <li class="nav-item"><a href="index.php" class="nav-link"><i class="nav-icon fas fa-chart-line"></i><p>Dashboard</p></a></li>
                        <li class="nav-item"><a href="manage_products.php" class="nav-link"><i class="nav-icon fas fa-spray-can"></i><p>Manage Perfumes</p></a></li>
                        <li class="nav-item"><a href="manage_orders.php" class="nav-link active"><i class="nav-icon fas fa-shopping-bag"></i><p>Orders Panel</p></a></li>
                        <li class="nav-header border-top border-secondary my-2"></li>
                        <li class="nav-item"><a href="../index.php" class="nav-link" target="_blank"><i class="nav-icon fas fa-globe"></i><p>View Live Store</p></a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <main class="app-main">
            <div class="app-content-header py-3 bg-white border-bottom mb-4">
                <div class="container-fluid">
                    <h3 class="mb-0 text-dark fw-semibold">Customer Orders</h3>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">Order ID</th>
                                            <th>Customer Details</th>
                                            <th>Items</th>
                                            <th>Total Price</th>
                                            <th>Status</th>
                                            <th class="pe-3 text-center">Update Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="ps-3 fw-semibold">#AE-1025</td>
                                            <td>
                                                <div class="fw-bold">Zayd Ali</div>
                                                <small class="text-muted">zayd@example.com | Lahore</small>
                                            </td>
                                            <td>Mystic Rose (50ml) x 1</td>
                                            <td>Rs. 3,800</td>
                                            <td><span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Pending</span></td>
                                            <td class="pe-3 text-center">
                                                <button class="btn btn-sm btn-success me-1"><i class="fas fa-check me-1"></i> Accept</button>
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-3 fw-semibold">#AE-1024</td>
                                            <td>
                                                <div class="fw-bold">Ayesha Khan</div>
                                                <small class="text-muted">ayesha@example.com | Karachi</small>
                                            </td>
                                            <td>Velvet Oud (100ml) x 2</td>
                                            <td>Rs. 9,000</td>
                                            <td><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Delivered</span></td>
                                            <td class="pe-3 text-center">
                                                <span class="text-muted small"><i class="fas fa-check-double text-success me-1"></i> Completed</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

    </div>

    <script src="dist/js/adminlte.min.js"></script>
</body>
</html>