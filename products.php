<?php
require_once 'config.php';
?>
<?php
require_once 'header.php';
?>
<section class="section" style="padding-top:120px;">
    <div class="fade-up">
        <p class="section-label">The Collection</p>
        <h2 class="section-title">All Fragrances</h2>
        <p class="section-desc">Filter by the mood you wish to embody. Each vibe is a doorway to a different emotional landscape.</p>
    </div>
    <div class="vibe-filter fade-up">
        <button class="vibe-btn <?php echo (!isset($_GET['vibe']) || $_GET['vibe'] === 'All') ? 'active' : ''; ?>" onclick="filterVibe('All')">All</button>
        <button class="vibe-btn <?php echo (isset($_GET['vibe']) && $_GET['vibe'] === 'Fresh') ? 'active' : ''; ?>" onclick="filterVibe('Fresh')">Fresh</button>
        <button class="vibe-btn <?php echo (isset($_GET['vibe']) && $_GET['vibe'] === 'Romantic') ? 'active' : ''; ?>" onclick="filterVibe('Attar')">Attar</button>
        <button class="vibe-btn <?php echo (isset($_GET['vibe']) && $_GET['vibe'] === 'Woody') ? 'active' : ''; ?>" onclick="filterVibe('Woody')">Woody</button>
        <button class="vibe-btn <?php echo (isset($_GET['vibe']) && $_GET['vibe'] === 'Body Mist') ? 'active' : ''; ?>" onclick="filterVibe('Body Mist')">Body Mist</button>
    </div>
    <div class="product-grid fade-up">
        <?php
        $vibe = isset($_GET['vibe']) ? $_GET['vibe'] : 'All';
        if ($vibe === 'All') {
            $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
        } else {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE vibe = ? ORDER BY id ASC");
            $stmt->execute([$vibe]);
        }
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($products) === 0):
        ?>
            <p style="color:var(--text-light); font-weight:300; grid-column:1/-1; text-align:center; padding:60px 0;">
                No fragrances found in this category.
            </p>
        <?php else: ?>
            <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <div class="product-card-img" onclick="openPyramid('<?php echo addslashes($p['name']); ?>', '<?php echo addslashes($p['top_notes']); ?>', '<?php echo addslashes($p['middle_notes']); ?>', '<?php echo addslashes($p['base_notes']); ?>')">
                        <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                        <span class="product-card-vibe"><?php echo htmlspecialchars($p['vibe']); ?></span>
                    </div>
                    <div class="product-card-body">
                        <h3 class="product-card-name"><?php echo htmlspecialchars($p['name']); ?></h3>
                        <p class="product-card-desc"><?php echo htmlspecialchars($p['description']); ?></p>
                        <div class="product-card-footer">
                            <span class="product-card-price"><?php echo formatPricePKR($p['price']); ?></span>
                            <form method="POST" action="cart.php">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                <button type="submit" class="btn-add-cart">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<script>
function filterVibe(vibe) {
    const url = new URL(window.location.href);
    if (vibe === 'All') {
        url.searchParams.delete('vibe');
    } else {
        url.searchParams.set('vibe', vibe);
    }
    window.location.href = url.toString();
}
</script>
<?php
require_once 'footer.php';
?>