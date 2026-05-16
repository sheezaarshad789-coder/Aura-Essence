<?php include('../config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Essence | Manage Perfumes</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
                <span class="navbar-text fw-bold text-uppercase ms-2">Aura & Essence — Products Portal</span>
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
                        <li class="nav-item"><a href="manage_products.php" class="nav-link active"><i class="nav-icon fas fa-spray-can"></i><p>Manage Perfumes</p></a></li>
                        <li class="nav-item"><a href="manage_orders.php" class="nav-link"><i class="nav-icon fas fa-shopping-bag"></i><p>Orders Panel</p></a></li>
                        <li class="nav-header border-top border-secondary my-2"></li>
                        <li class="nav-item"><a href="../index.php" class="nav-link" target="_blank"><i class="nav-icon fas fa-globe"></i><p>View Live Store</p></a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <main class="app-main">
            <div class="app-content-header py-3 bg-white border-bottom mb-4">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-dark fw-semibold">Fragrance Inventory</h3>
                        <button class="btn btn-primary btn-sm"><i class="fas fa-plus me-2"></i>Add New Perfume</button>
                    </div>
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
                                            <th class="ps-3">ID</th>
                                            <th>Image</th>
                                            <th>Perfume Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th class="pe-3 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="ps-3 fw-semibold">#1</td>
                                            <td><img src="../images/velvet_oud.jpg" alt="Perfume" class="rounded" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/40'"></td>
                                            <td class="fw-semibold">Velvet Oud (100ml)</td>
                                            <td><span class="badge bg-secondary-subtle text-secondary px-2 py-1">Oud / Woody</span></td>
                                            <td>Rs. 4,500</td>
                                            <td><span class="text-success fw-bold">24 Left</span></td>
                                            <td class="pe-3 text-center">
                                                <button class="btn btn-sm btn-outline-info me-1"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-3 fw-semibold">#2</td>
                                            <td><img src="../images/mystic_rose.jpg" alt="Perfume" class="rounded" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/40'"></td>
                                            <td class="fw-semibold">Mystic Rose (50ml)</td>
                                            <td><span class="badge bg-secondary-subtle text-secondary px-2 py-1">Floral</span></td>
                                            <td>Rs. 3,800</td>
                                            <td><span class="text-danger fw-bold">3 Low Stock</span></td>
                                            <td class="pe-3 text-center">
                                                <button class="btn btn-sm btn-outline-info me-1"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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