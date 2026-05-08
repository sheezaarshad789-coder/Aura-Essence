<?php
require_once 'config.php';
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header('Location: login.php');
    exit();
}
$cart = getCurrentCart();
if (empty($cart)) {
    header('Location: products.php');
    exit();
}
$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute(array_values($ids));
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = 0;
foreach ($products as $p) {
    $total += $p['price'] * $cart[$p['id']];
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $area = trim($_POST['area'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'cod';

    if ($full_name === '' || $email === '' || $phone === '' || $address === '' || $area === '' || $city === '' || $province === '') {
        $error = 'Please fill in all shipping details.';
    } elseif (!preg_match('/^[0-9+\-\s]{10,16}$/', $phone)) {
        $error = 'Please enter a valid phone number.';
    } elseif ($payment_method !== 'cod') {
        $error = 'Only Cash on Delivery is available right now.';
    } else {
        $full_address = "$address, $area, $city, $province | Phone: $phone | Payment: Cash on Delivery";
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, full_name, email, address, total) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $full_name, $email, $full_address, $total]);
            $order_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
            foreach ($products as $p) {
                $stmt->execute([
                    $order_id,
                    $p['id'],
                    $p['name'],
                    $p['price'],
                    $cart[$p['id']]
                    ]);
            }
            $pdo->commit();
            saveUserCart([]);
             header('Location: order_success.php?order_id=' . $order_id);
            exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = 'Something went wrong. Please try again.';
    }
    }
}
?>
<?php
require_once 'header.php';
?>
<main class="checkout-page">
    <div class="fade-up">
        <p class="section-label">Checkout</p>
        <h2 class="section-title" style="font-size:32px;">Complete Your Order</h2>
    </div>
    <?php if ($error): ?>
        <p class="form-error" style="margin:20px 0;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <div class="checkout-grid fade-up">
            <div class="checkout-form-section">
                <h2>Shipping Details</h2>
                <form method="POST" class="checkout-form">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" placeholder="03XX-XXXXXXX" required>
                    </div>
                    <div class="form-group">
                        <label for="address">House / Street Address</label>
                        <input type="text" id="address" name="address" placeholder="House 12, Street 5" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="area">Area</label>
                            <input type="text" id="area" name="area" placeholder="DHA / Gulshan / Johar Town" required>
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" placeholder="Karachi / Lahore / Islamabad" required>
                        </div>  
                    </div>
                    <div class="form-group">
                        <label for="province">Province</label>
                        <select id="province" name="province" required style="padding: 14px 16px; border: 1px solid #d0d7de; background: #ffffff; font-size: 15px; font-family: 'DM Sans', sans-serif; color: #1f2328; border-radius: 3px; outline: none;">
                            <option value="">Select Province</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Sindh">Sindh</option>
                            <option value="Khyber Pakhtunkhwa">Khyber Pakhtunkhwa</option>
                            <option value="Balochistan">Balochistan</option>
                            <option value="Islamabad Capital Territory">Islamabad Capital Territory</option>
                            <option value="Azad Kashmir">Azad Kashmir</option>
                            <option value="Gilgit-Baltistan">Gilgit-Baltistan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <label style="display:flex; align-items:center; gap:10px; font-size:14px; text-transform:none; letter-spacing:0; font-weight:400; color:#1f2328;">
                            <input type="radio" name="payment_method" value="cod" checked>
                            Cash on Delivery
                        </label>
                    </div>
                    <button type="submit" class="btn-submit" style="margin-top:12px;">Place Order — <?php echo formatPricePKR($total); ?></button>
                </form>
            </div>
            <div class="checkout-order-summary">
                <h3>Order Summary</h3>
                <?php foreach ($products as $p): ?>
                   <div class="checkout-summary-item">
                    <span><?php echo htmlspecialchars($p['name']); ?> × <?php echo $cart[$p['id']]; ?></span>
                    <span><?php echo formatPricePKR($p['price'] * $cart[$p['id']]); ?></span>
                   </div> 
                <?php endforeach; ?>
              <hr class="checkout-summary-divider">
                <div class="checkout-summary-item" style="color:var(--text-light);">
                    <span>Shipping</span>
                    <span>Complimentary</span>
                </div> 
                <hr class="checkout-summary-divider">
                <div class="checkout-summary-total">
                    <span>Total</span>
                    <span><?php echo formatPricePKR($total); ?></span>
                </div>
            </div>
        </div>
</main>
<?php
require_once 'footer.php';
?>