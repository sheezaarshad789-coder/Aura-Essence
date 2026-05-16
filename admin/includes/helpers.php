<?php

function adminOrderCode(int $id): string
{
    return '#AE-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT);
}

function adminStatusBadge(string $status): string
{
    $key = strtolower(trim($status));
    $classes = [
        'delivered' => 'bg-success-subtle text-success border border-success-subtle',
        'completed' => 'bg-success-subtle text-success border border-success-subtle',
        'pending'   => 'bg-warning-subtle text-warning border border-warning-subtle',
        'cancelled' => 'bg-danger-subtle text-danger border border-danger-subtle',
        'processing'=> 'bg-info-subtle text-info border border-info-subtle',
    ];
    $class = $classes[$key] ?? 'bg-secondary-subtle text-secondary border border-secondary-subtle';
    $label = ucfirst($key);

    return '<span class="badge ' . $class . ' px-2 py-1">' . htmlspecialchars($label) . '</span>';
}

function adminExtractCity(string $address): string
{
    $parts = explode('|', $address, 2);
    $location = trim($parts[0] ?? '');
    $segments = array_map('trim', explode(',', $location));
    return $segments[2] ?? ($segments[1] ?? $location);
}

function adminOrderItemsSummary(PDO $pdo, int $orderId): string
{
    $stmt = $pdo->prepare(
        'SELECT product_name, quantity FROM order_items WHERE order_id = ? ORDER BY id ASC'
    );
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$items) {
        return '—';
    }

    $parts = [];
    foreach ($items as $item) {
        $qty = (int) $item['quantity'];
        $name = $item['product_name'];
        $parts[] = $qty > 1 ? "{$name} x {$qty}" : $name;
    }

    return implode(', ', $parts);
}

function adminProductImageUrl(string $image): string
{
    if (preg_match('#^https?://#i', $image)) {
        return $image;
    }

    return '../' . ltrim($image, '/');
}

function adminDashboardStats(PDO $pdo): array
{
    $totalProducts = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    $pendingOrders = (int) $pdo->query("SELECT COUNT(*) FROM orders WHERE LOWER(status) = 'pending'")->fetchColumn();
    $totalRevenue = (float) $pdo->query('SELECT COALESCE(SUM(total), 0) FROM orders')->fetchColumn();

    return [
        'total_products'  => $totalProducts,
        'pending_orders'  => $pendingOrders,
        'total_revenue'   => $totalRevenue,
    ];
}

function adminLatestOrders(PDO $pdo, int $limit = 8): array
{
    $stmt = $pdo->prepare('SELECT * FROM orders ORDER BY created_at DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function adminProductVibes(): array
{
    return ['Fresh', 'Woody', 'Romantic', 'Body Mist', 'Attar'];
}

function adminGetProduct(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function adminProductHasOrders(PDO $pdo, int $productId): bool
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM order_items WHERE product_id = ?');
    $stmt->execute([$productId]);

    return (int) $stmt->fetchColumn() > 0;
}

function adminUploadProductImage(array $file): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok' => false, 'error' => 'Please choose an image file.'];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Image upload failed. Try again.'];
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        return ['ok' => false, 'error' => 'Only JPG, PNG, WEBP, or GIF images are allowed.'];
    }

    $uploadDir = dirname(__DIR__, 2) . '/images';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        return ['ok' => false, 'error' => 'Could not create images folder.'];
    }

    $base = preg_replace('/[^a-z0-9_-]+/i', '-', pathinfo($file['name'], PATHINFO_FILENAME));
    $base = trim($base, '-') ?: 'product';
    $filename = strtolower($base) . '-' . time() . '.' . $allowed[$mime];
    $target = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        return ['ok' => false, 'error' => 'Could not save uploaded image.'];
    }

    return ['ok' => true, 'path' => 'images/' . $filename];
}

function adminSaveProduct(PDO $pdo, array $data, ?int $id = null): array
{
    $name = trim($data['name'] ?? '');
    $description = trim($data['description'] ?? '');
    $price = (float) ($data['price'] ?? 0);
    $vibe = trim($data['vibe'] ?? '');
    $top = trim($data['top_notes'] ?? '');
    $middle = trim($data['middle_notes'] ?? '');
    $base = trim($data['base_notes'] ?? '');
    $image = trim($data['image'] ?? '');

    if ($name === '' || $description === '' || $price <= 0 || $vibe === '') {
        return ['ok' => false, 'error' => 'Name, description, price, and vibe are required.'];
    }

    if (!in_array($vibe, adminProductVibes(), true)) {
        return ['ok' => false, 'error' => 'Please select a valid vibe.'];
    }

    if ($image === '') {
        return ['ok' => false, 'error' => 'Product image is required.'];
    }

    if ($id) {
        $existing = adminGetProduct($pdo, $id);
        if (!$existing) {
            return ['ok' => false, 'error' => 'Product not found.'];
        }

        $stmt = $pdo->prepare(
            'UPDATE products SET name = ?, description = ?, price = ?, vibe = ?, top_notes = ?, middle_notes = ?, base_notes = ?, image = ? WHERE id = ?'
        );
        $stmt->execute([$name, $description, $price, $vibe, $top, $middle, $base, $image, $id]);

        return ['ok' => true, 'id' => $id];
    }

    $stmt = $pdo->prepare(
        'INSERT INTO products (name, description, price, vibe, top_notes, middle_notes, base_notes, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$name, $description, $price, $vibe, $top, $middle, $base, $image]);

    return ['ok' => true, 'id' => (int) $pdo->lastInsertId()];
}

function adminDeleteProduct(PDO $pdo, int $id): array
{
    $product = adminGetProduct($pdo, $id);
    if (!$product) {
        return ['ok' => false, 'error' => 'Product not found.'];
    }

    if (adminProductHasOrders($pdo, $id)) {
        return ['ok' => false, 'error' => 'Cannot delete: this perfume exists in customer orders.'];
    }

    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);

    $imagePath = dirname(__DIR__, 2) . '/' . ltrim($product['image'], '/');
    if (str_starts_with($product['image'], 'images/') && is_file($imagePath)) {
        @unlink($imagePath);
    }

    return ['ok' => true];
}
