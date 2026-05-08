<?php
require_once 'config.php';
if(isLoggedIn()) {
    header('Location: products.php');
    exit();
}
$error = '';
$success = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ??'');
    $password = $_POST['password'] ??'';
    $confirm_password = $_POST['confirm_password'] ??'';

    if($name === '' || $email === '' || $password === '' || $confirm_password === '') {
        $error .= 'Please Fill int all fields.';
    } elseif (strlen($password) < 6) {
        $error .= 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm_password) {
        $error .= 'Passwords do not match.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetch()) {
            $error = 'An account with this email already exists.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed]);
            $success = 'Account created successfully. You can now sign in.';
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
            <h1>Create Account</h1>
            <p>Already have an account? <a href="login.php">Sign in</a></p>
        </div>
    <?php if ($error): ?>
        <p class="form-error" style="text-align:center; margin-bottom:16px;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="form-success" style="margin-bottom:16px;"><?php echo htmlspecialchars($success); ?></p>
        <div style="text-align:center;">
            <a href="login.php" class="btn-submit" style="display:inline-block; padding:16px 48px; color:var(--linen);">Go to Sign In</a>
        </div>
    <?php else: ?>
    <form method="POST" class="register-form">
        <div class="form-group">
            <label for="name"> Full Name</label>
            <input type="text" id="name" name="name" placeholder="Your full name" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="your@email.com" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="At least 6 characters" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
        </div>
        <button type="submit" class="btn-submit">Create Account</button>
    </form>
    <?php endif; ?>
    </div>
</main>
<?php
require_once 'footer.php';
?>