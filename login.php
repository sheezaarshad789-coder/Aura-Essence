<?php
require_once 'config.php';
if (isLoggedIn()) {
    header('Location: products.php');
    exit;
}
 $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            migrateCartToSession();
            $redirect = $_SESSION['redirect_after_login'] ?? 'products.php';
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<?php
require_once 'header.php';
?>
<main class="auth-page">
    <div class="auth-container fade-up">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p>Don't have an account? <a href="register.php">Create one</a></p>
        </div>
        <?php if ($error): ?>
            <p class="form-error" style="text-align:center; margin-bottom:16px;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-submit">Sign In</button>
        </form>
    </div>
</main>
<?php
require_once 'footer.php';
?>