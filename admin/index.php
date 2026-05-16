<?php
require_once __DIR__ . '/includes/init.php';

$stats = adminDashboardStats($pdo);
$latestOrders = adminLatestOrders($pdo, 8);

$pageTitle = 'Aura Essence | Admin Dashboard';
$activeNav = 'dashboard';
$portalLabel = 'Aura & Essence — Management Portal';

require __DIR__ . '/includes/header.php';
?>

<div class="app-content-header py-3 bg-white border-bottom mb-4">
    <div class="container-fluid">
        <h3 class="mb-0 text-dark fw-semibold">Dashboard Overview</h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box shadow-sm p-3 bg-white rounded border-start border-primary border-4">
                    <span class="info-box-icon text-primary fs-1 me-3"><i class="fas fa-flask"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted fw-medium">Total Fragrances</span>
                        <h4 class="info-box-number mb-0 fw-bold"><?php echo (int) $stats['total_products']; ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box shadow-sm p-3 bg-white rounded border-start border-warning border-4">
                    <span class="info-box-icon text-warning fs-1 me-3"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted fw-medium">Pending Orders</span>
                        <h4 class="info-box-number mb-0 fw-bold"><?php echo (int) $stats['pending_orders']; ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box shadow-sm p-3 bg-white rounded border-start border-success border-4">
                    <span class="info-box-icon text-success fs-1 me-3"><i class="fas fa-coins"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted fw-medium">Total Revenue</span>
                        <h4 class="info-box-number mb-0 fw-bold"><?php echo formatPricePKR($stats['total_revenue']); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold text-secondary">Latest Orders</h5>
                <a href="manage_orders.php" class="btn btn-sm btn-outline-secondary">View all</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Order ID</th>
                                <th>Customer Name</th>
                                <th>Item Ordered</th>
                                <th>Total Price</th>
                                <th class="pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($latestOrders)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">No orders yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($latestOrders as $order): ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold"><?php echo htmlspecialchars(adminOrderCode((int) $order['id'])); ?></td>
                                        <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars(adminOrderItemsSummary($pdo, (int) $order['id'])); ?></td>
                                        <td><?php echo formatPricePKR($order['total']); ?></td>
                                        <td class="pe-3"><?php echo adminStatusBadge($order['status'] ?? 'pending'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
