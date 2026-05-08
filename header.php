<?php
require_once 'config.php';
migrateCartTOSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura & Essence</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime(__DIR__ . '/style.css'); ?>">
</head>
<body>
    <!--Navigation--->
    <nav class="nav-bar">
        <div class="nav-inner">
            <a href="index.php" class="nav-logo">Aura <span>&</span> Essence</a>
            <button class="nav-menu-btn" onclick="toggleMobileMenu()" aria-label="Menu">
            <span></span><span></span><span></span>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Collection</a></li>
                <li class="nav-cart" onclick="openCartDrawer()">
                   <a href="javascript:void(0)">Cart</a>
                   <?php $count = getCartCount(); ?>
                   <?php if ($count > 0): ?>
                       <span class="cart-badge"><?php echo $count; ?></span>
                   <?php endif; ?> 
               </li>
               <li class="nav-auth">
                <?php if (isLoggedIn()): ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Sign In</a>
                <?php endif; ?>
               </li>
            </ul>
        </div>
    </nav>

    <!-- Cart Drawer Overlay --->
     <div class="cart-drawer-overlay" id="cartOverlay" onclick="closeCartDrawer()">
     </div>

     <!-- Cart Drawer --->
      <aside class="cart-drawer" id="cartDrawer">
        <div class="cart-drawer-header">
            <h2 class="cart-drawer-title">Your Cart</h2>
            <button class="cart-drawer-close" onclick="closeCartDrawer()">&times;</button>
        </div>
        <div class="cart-drawer-body" id="cartDrawerBody">
            <?php
            $cart = getCurrentCart();
            if(empty($cart)):
            ?>
            <div class="cart-drawer-empty">
                Your Cart is Empty. <br>Explore our collection to find your scent.
            </div>
            <?php else: ?>
                <?php
                $ids = array_keys($cart);
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
                $stmt->execute(array_values($ids));
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $total = 0;
                  foreach ($products as $p):
                    $qty = $cart[$p['id']];
                    $subtotal = $p['price'] * $qty;
                    $total += $subtotal;
                ?>
                <div class="cart-item">
                    <div class="cart-item-img">
                        <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                    </div>
                    <div class="cart-item-info">
                        <div class="cart-item-name">
                            <?php echo htmlspecialchars($p['name']); ?>
                        </div>
                        <div class="cart-item-price">
                            <?php echo formatPricePKR($p['price']); ?>
                        </div>
                        <div class="cart-item-qty">
                            <form method="POST" action="cart.php" class="cart-item-qty-form">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                <button type="submit" name="change" value="-1">&minus;</button>
                                <span><?php echo $qty; ?></span>
                                <button type="submit" name="change" value="1">+</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
        </div>
        <?php if(!empty($cart)): ?>
            <div class="cart-drawer-footer">
                <div class="cart-total-row">
                    <span>Total:</span>
                    <span><?php echo formatPricePKR($total); ?></span>
                </div>
                <a href="checkout.php" class="btn-checkout" onclick="closeCartDrawer()">Proceed to Checkout</a>
                <?php if(!isLoggedIn()): ?>
                    <p class="cart-guest-note">
                        <a href="login.php">Sign in</a> to Complete your purchase
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
      </aside>
      
      <div class="pyramid-overlay" id="pyramidOverlay" onclick="closePyramid(event)">
        <div class="pyramid-modal" onclick="event.stopPropagation()">
            <button class="pyramid-close" onclick="document.getElementById('pyramidOverlay').classList.remove('open')">&times;</button>
            <h3 class="pyramid-name" id="pyramidName"></h3>
            <div class="pyramid-layer top">
                <div class="pyramid-layer-notes">Top Notes</div>
                <div class="pyramid-layer-notes" id="pyramidTOP"></div>
            </div>
            <div class="pyramid-layer middle">
                <div class="pyramid-layer-label">Base Notes</div>
                <div class="pyramid-layer-notes" id="pyramidBase"></div>
            </div>
        </div>
      </div>

      <div class="toast" id="toast"></div>