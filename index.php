<?php
require_once 'config.php';
?>
<?php
require_once 'header.php';
?>
<section class="hero">
    <div class="hero-bg">
        <img src="https://picsum.photos/seed/aura-hero-luxury/1920/1080" alt="Aura & Essence">
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content fade-up">
        <p class="hero-label">Established 2026</p>
        <h1 class="hero-title">The Art of <br><em>Invisible</em> Beauty</h1>
        <p class="hero-desc">Fragrances that exist at the intersection of Japanese minimalism and Scandinavian warmth. Each scent is a quiet story told in notes.</p>
        <a href="products.php" class="hero-btn">Discover Collection</a>
    </div>
</section>
<section class="philosophy" id="our-philosophy">
    <div class="philosophy-img fade-up">
        <img src="https://picsum.photos/seed/aura-philosophy/800/1000" alt="Perfume ingredients">
    </div>
    <div class="philosophy-text fade-up">
        <p class="section-label">Our Philosophy</p>
        <h2 class="section-title">Less, but<br>more meaningful</h2>
        <p class="section-desc">We believe luxury is not abundance - it is intention. Every ingredient is sourced with reverence, every composition distilled to its purest expression.</p>
        <div class="philosophy-features">
            <div class="philosophy-feature-icon">
                ◈
            </div>
            <div>
                <h4>Natural Integrity</h4>
                <p>Only the finest natural extracts and responsibly harvested raw materials.</p>
            </div>
        </div>
        <div class="philosophy-feature">
            <div class="philosophy-feature-icon">◈</div>
            <div>
                <h4>Japandi Design</h4>
                <p>Bottles crafted as objects of quiet beauty — minimal, tactile, timeless.</p>
            </div>
        </div>
        <div class="philosophy-feature">
            <div class="philosophy-feature-icon">◈</div>
            <div>
                <h4>Scent as Ritual</h4>
                <p>Each fragrance is designed to become a meditative part of your daily rhythm.</p>
            </div>
            </div>
        </div>
    </div>
</section>
<section class="section" id="ingredients">
    <div class="fade-up">
        <p class="section-label">Ingredients</p>
        <h2 class="section-title">Pure Origins</h2>
        <p class="section-desc">From citrus peels to rare woods, each raw material is selected for clarity, longevity, and ethical sourcing.</p>
    </div>
</section>
<section class="section" id="sustainability">
    <div class="fade-up">
        <p class="section-label">Sustainability</p>
        <h2 class="section-title">Responsible Craft</h2>
        <p class="section-desc">We prioritize refillable design, mindful packaging, and partnerships that protect biodiversity at the source.</p>
    </div>
</section>
<section class="section" id="journal">
    <div class="fade-up">
        <p class="section-label">Journal</p>
        <h2 class="section-title">Stories in Scent</h2>
        <p class="section-desc">Notes, rituals, and seasonal inspirations from the world of Aura & Essence.</p>
    </div>
</section>
<section class="section">
    <div class="fade-up">
        <p class="section-label">The Collection</p>
        <h2 class="section-title">Curated Essences</h2>
        <p class="section-desc">Eight compositions, each exploring a different facet of emotional depth through scent.</p>
    </div>
    <div class="home-featured-stack fade-up">
        <?php
        $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC LIMIT 3");
        $featured = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($featured as $p):
        ?>
        <div class="product-card home-featured-card">
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
    </div>
    <div style="text-align:center; margin-top:48px;" class="fade-up">
        <a href="products.php" class="hero-btn" style="color:#1f2328; border-color:#7a8fa8;">View All Fragrances</a>
    </div>
</section>
<?php
require_once 'footer.php';
?>