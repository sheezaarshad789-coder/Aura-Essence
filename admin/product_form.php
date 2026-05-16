<?php
require_once __DIR__ . '/includes/init.php';

$id = (int) ($_GET['id'] ?? 0);
$product = $id ? adminGetProduct($pdo, $id) : null;

if ($id && !$product) {
    header('Location: manage_products.php?error=' . urlencode('Product not found.'));
    exit;
}

$error = $_SESSION['product_form_error'] ?? '';
$old = $_SESSION['product_form_data'] ?? [];
unset($_SESSION['product_form_error'], $_SESSION['product_form_data']);

$values = [
    'name'          => $old['name'] ?? $product['name'] ?? '',
    'description'   => $old['description'] ?? $product['description'] ?? '',
    'price'         => $old['price'] ?? $product['price'] ?? '',
    'vibe'          => $old['vibe'] ?? $product['vibe'] ?? '',
    'top_notes'     => $old['top_notes'] ?? $product['top_notes'] ?? '',
    'middle_notes'  => $old['middle_notes'] ?? $product['middle_notes'] ?? '',
    'base_notes'    => $old['base_notes'] ?? $product['base_notes'] ?? '',
    'image'         => $product['image'] ?? '',
];

$isEdit = (bool) $product;
$pageTitle = $isEdit ? 'Edit Perfume | Aura Essence' : 'Add Perfume | Aura Essence';
$activeNav = 'products';
$portalLabel = 'Aura & Essence — Products Portal';

require __DIR__ . '/includes/header.php';
?>

<div class="app-content-header py-3 bg-white border-bottom mb-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3 class="mb-0"><?php echo $isEdit ? 'Edit Perfume' : 'Add New Perfume'; ?></h3>
            <a href="manage_products.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to list
            </a>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="product_action.php" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="save">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?php echo (int) $product['id']; ?>">
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($values['image']); ?>">
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Perfume Name *</label>
                            <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($values['name']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Price (PKR) *</label>
                            <input type="number" name="price" class="form-control" min="1" step="0.01" required value="<?php echo htmlspecialchars((string) $values['price']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Vibe *</label>
                            <select name="vibe" class="form-select" required>
                                <option value="">Select vibe</option>
                                <?php foreach (adminProductVibes() as $vibe): ?>
                                    <option value="<?php echo htmlspecialchars($vibe); ?>"<?php echo $values['vibe'] === $vibe ? ' selected' : ''; ?>>
                                        <?php echo htmlspecialchars($vibe); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description *</label>
                            <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($values['description']); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Top Notes</label>
                            <input type="text" name="top_notes" class="form-control" value="<?php echo htmlspecialchars($values['top_notes']); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Notes</label>
                            <input type="text" name="middle_notes" class="form-control" value="<?php echo htmlspecialchars($values['middle_notes']); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Base Notes</label>
                            <input type="text" name="base_notes" class="form-control" value="<?php echo htmlspecialchars($values['base_notes']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo $isEdit ? 'Replace Image (optional)' : 'Product Image *'; ?></label>
                            <input type="file" name="image_file" class="form-control" accept="image/*"<?php echo $isEdit ? '' : ' required'; ?>>
                            <div class="form-text">Saved in website <code>images/</code> folder (JPG, PNG, WEBP, GIF).</div>
                        </div>
                        <?php if ($isEdit && $values['image']): ?>
                            <div class="col-md-6">
                                <label class="form-label">Current Image</label>
                                <div>
                                    <img src="<?php echo htmlspecialchars(adminProductImageUrl($values['image'])); ?>" alt="Current" class="rounded border" style="max-height:120px;">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>
                            <?php echo $isEdit ? 'Update Perfume' : 'Add Perfume'; ?>
                        </button>
                        <a href="manage_products.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
