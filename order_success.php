<?php
require_once 'config.php';

if(!isLoggedIn()){
    header('Location: index.php');
    exit();
}

$order_id = intval($_GET['order_id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$order) {
    header('Location: products.php');
    exit();
}
?>
<?php
require_once 'header.php';
?>
<main class="success-page">
    <div class="success-content fade-up">
        <div class="success-icon">✓</div>
        <h1>Thank You for Your Order!</h1>
        <p>Your order <strong>#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></strong> has been successfully placed.</p>
    </div>
</main>

<?php
require_once 'footer.php';
?>