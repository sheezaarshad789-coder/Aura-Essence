<?php
require_once __DIR__ . '/includes/init.php';

$action = $_POST['action'] ?? '';

if ($action === 'delete') {
    $id = (int) ($_POST['id'] ?? 0);
    $result = adminDeleteProduct($pdo, $id);
    $param = $result['ok'] ? 'deleted=1' : 'error=' . urlencode($result['error']);
    header('Location: manage_products.php?' . $param);
    exit;
}

if ($action === 'save') {
    $id = (int) ($_POST['id'] ?? 0);
    $image = trim($_POST['current_image'] ?? '');

    if (!empty($_FILES['image_file']['name'])) {
        $upload = adminUploadProductImage($_FILES['image_file']);
        if (!$upload['ok']) {
            $_SESSION['product_form_error'] = $upload['error'];
            $_SESSION['product_form_data'] = $_POST;
            $redirect = $id ? "product_form.php?id=$id" : 'product_form.php';
            header('Location: ' . $redirect);
            exit;
        }
        $image = $upload['path'];
    }

    $result = adminSaveProduct($pdo, array_merge($_POST, ['image' => $image]), $id ?: null);

    if (!$result['ok']) {
        $_SESSION['product_form_error'] = $result['error'];
        $_SESSION['product_form_data'] = $_POST;
        $redirect = $id ? "product_form.php?id=$id" : 'product_form.php';
        header('Location: ' . $redirect);
        exit;
    }

    header('Location: manage_products.php?saved=1');
    exit;
}

header('Location: manage_products.php');
exit;
