<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = intval($_POST['product_id'] ?? 0);
    if ($product_id > 0) {
        if ($action === 'add') {
            if (isLoggedIn()) {
                addToUserCart($product_id, 1);
            } else {
                addToGuestCart($product_id, 1);
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        if ($action === 'update') {
            $change = intval($_POST['change'] ?? 0);
            if (isLoggedIn()) {
                $cart = getUserCart();
                if (isset($cart[$product_id])) {
                    $cart[$product_id] += $change;
                    if ($cart[$product_id] <= 0) {
                        unset($cart[$product_id]);
                    }
                    saveUserCart($cart);
                }
            } else {
                $cart = getGuestCart();
                if (isset($cart[$product_id])) {
                    $cart[$product_id] += $change;
                    if ($cart[$product_id] <= 0) {
                        unset($cart[$product_id]);
                    }
                    saveGuestCart($cart);
                }
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        if ($action === 'remove') {
            if (isLoggedIn()) {
                removeFromUserCart($product_id);
            } else {
                removeFromGuestCart($product_id);
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}
header('Location: products.php');
exit;