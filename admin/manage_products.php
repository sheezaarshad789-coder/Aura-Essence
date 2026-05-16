<?php
require_once __DIR__ . '/includes/init.php';

$products = $pdo->query('SELECT * FROM products ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);

$flash = '';
if (isset($_GET['saved'])) {
    $flash = 'Product saved successfully.';
} elseif (isset($_GET['deleted'])) {
    $flash = 'Product deleted successfully.';
} elseif (isset($_GET['error'])) {
    $flash = $_GET['error'];
}

$pageTitle = 'Aura Essence | Manage Perfumes';
$activeNav = 'products';
$portalLabel = 'Aura & Essence — Products Portal';

require __DIR__ . '/includes/header.php';
?>

<div class="app-content-header py-3 bg-white border-bottom mb-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3 class="mb-0">Fragrance Inventory</h3>
            <a href="product_form.php" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add New Perfume
            </a>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <?php if ($flash): ?>
            <div class="alert alert-<?php echo isset($_GET['error']) ? 'danger' : 'success'; ?> border-0 shadow-sm">
                <?php echo htmlspecialchars($flash); ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <span class="text-muted small"><?php echo count($products); ?> products in catalog</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Image</th>
                                <th>Perfume Name</th>
                                <th>Vibe</th>
                                <th>Price</th>
                                <th class="pe-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        No fragrances yet.
                                        <a href="product_form.php">Add your first perfume</a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold">#<?php echo (int) $p['id']; ?></td>
                                        <td>
                                            <img
                                                src="<?php echo htmlspecialchars(adminProductImageUrl($p['image'])); ?>"
                                                alt="<?php echo htmlspecialchars($p['name']); ?>"
                                                class="rounded"
                                                style="width: 48px; height: 48px; object-fit: cover;"
                                            >
                                        </td>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($p['name']); ?></td>
                                        <td>
                                            <span class="badge text-bg-secondary"><?php echo htmlspecialchars($p['vibe']); ?></span>
                                        </td>
                                        <td><?php echo formatPricePKR($p['price']); ?></td>
                                        <td class="pe-3 text-center">
                                            <a href="product_form.php?id=<?php echo (int) $p['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="product_action.php" class="d-inline" onsubmit="return confirm('Delete this perfume?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo (int) $p['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
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
