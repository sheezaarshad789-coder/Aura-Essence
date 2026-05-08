<?php
session_start();

$host = 'localhost';
$dbname = 'aura_essence';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

function getGuestCart() {
    $cart =[];
    if (isset($_COOKIE['guest_cart'])) {
        $items = json_decode($_COOKIE['guest_cart'], true);
        if (is_array($items)) {
            $cart = $items;
        }
    }
    return $cart;
}
function saveGuestCart($cart) {
    setcookie('guest_cart', json_encode($cart), time() + (86400 * 7), '/');
}
function addToGuestCart($product_id, $quantity) {
    $cart = getGuestCart();
    if (isset($cart[$product_id])) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }
    saveGuestCart($cart);
}
function removeFromGuestCart($product_id) {
    $cart = getGuestCart();
    unset($cart[$product_id]);
    saveGuestCart($cart);
}
function getUserCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return $_SESSION['cart'];
}
function saveUserCart($cart) {
    $_SESSION['cart'] = $cart;
}
function addToUserCart($product_id, $quantity) {
    $cart = getUserCart();
    if(isset($cart[$product_id])) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }
    saveUserCart($cart);
}
function removeFromUserCart($product_id) {
    $cart = getUserCart();
    unset($cart[$product_id]);
    saveUserCart($cart);
}
function getCurrentCart() {
    if (isset($_SESSION['user_id'])) {
        return getUserCart();
    } else {
        return getGuestCart();
    }
}
function migrateCartTOSession() {
    if(isset($_SESSION['user_id']) && isset($_COOKIE['guest_cart'])) {
        $guest_cart = getGuestCart();
        $user_cart = getUserCart();
        foreach ($guest_cart as $pid => $qty) {
            if(isset($user_cart['$pid'])) {
                $user_cart['$pid'] = $qty;
            } else {
                $user_cart[$pid]  = $qty;
            }
        }
        saveUserCart($user_cart);
        setcookie('guest_cart', '', time() - 3600, '/');
    }
}
function getCartCount() {
    $cart = getCurrentCart();
    return array_sum($cart);
}
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function formatPricePKR($amount) {
    $normalized = is_string($amount) ? str_replace([',', ' '], '', $amount) : $amount;
    $value = (float)$normalized;
    $decimals = fmod($value, 1.0) === 0.0 ? 0 : 2;
    return 'PKR ' . number_format($value, $decimals);
}