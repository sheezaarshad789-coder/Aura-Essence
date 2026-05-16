<?php
require_once __DIR__ . '/includes/init.php';

$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = (int) $_POST['order_id'];
    $status = strtolower(trim($_POST['status']));
    $allowed = ['pending', 'processing', 'delivered', 'cancelled'];

    if ($orderId > 0 && in_array($status, $allowed, true)) {
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$status, $orderId]);
        $flash = 'Order status updated.';
    }
}

$orders = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Aura Essence | Manage Orders';
$activeNav = 'orders';
$portalLabel = 'Aura & Essence — Orders Portal';

require __DIR__ . '/includes/header.php';
?>

<div class="app-content-header py-3 bg-white border-bottom mb-4">
    <div class="container-fluid">
        <h3 class="mb-0 text-dark fw-semibold">Customer Orders</h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <?php if ($flash): ?>
            <div class="alert alert-success border-0 shadow-sm"><?php echo htmlspecialchars($flash); ?></div>
        <?php endif; ?>

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
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">No customer orders yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <?php
                                    $status = strtolower($order['status'] ?? 'pending');
                                    $city = adminExtractCity($order['address']);
                                    ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold"><?php echo htmlspecialchars(adminOrderCode((int) $order['id'])); ?></td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($order['full_name']); ?></div>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($order['email']); ?>
                                                <?php if ($city): ?> | <?php echo htmlspecialchars($city); ?><?php endif; ?>
                                            </small>
                                        </td>
                                        <td><?php echo htmlspecialchars(adminOrderItemsSummary($pdo, (int) $order['id'])); ?></td>
                                        <td><?php echo formatPricePKR($order['total']); ?></td>
                                        <td><?php echo adminStatusBadge($status); ?></td>
                                        <td class="pe-3 text-center">
                                            <?php if (in_array($status, ['delivered', 'cancelled'], true)): ?>
                                                <span class="text-muted small">
                                                    <i class="fas fa-check-double text-success me-1"></i>
                                                    <?php echo $status === 'delivered' ? 'Completed' : 'Closed'; ?>
                                                </span>
                                            <?php else: ?>
                                                <form method="POST" class="d-inline-flex flex-wrap gap-1 justify-content-center">
                                                    <input type="hidden" name="order_id" value="<?php echo (int) $order['id']; ?>">
                                                    <button type="submit" name="status" value="delivered" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check me-1"></i> Deliver
                                                    </button>
                                                    <button type="submit" name="status" value="cancelled" class="btn btn-sm btn-outline-danger" title="Cancel order">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
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
