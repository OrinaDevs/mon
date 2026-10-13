<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

require_once __DIR__ . '/includes/seo_helpers.php';

$page_title = 'Home';
$active_nav = 'home';
$extra_css = ['home.css'];

// Search engine and sharing tags
$seo_title = 'CCTV, Networking and Power Backup | ' . SITE_NAME;
$meta_description = 'CCTV cameras, networking components and power backups from Merchant of Nairobi. Deliveries countrywide. Browse the range and confirm stock with us.';
$canonical_path = '/';
$schema = [business_schema()];

$categories = db()->query(
    'SELECT c.name, c.slug, c.description, COUNT(p.id) AS product_count
     FROM categories c
     LEFT JOIN products p ON p.category_id = c.id
     GROUP BY c.id
     ORDER BY c.name'
)->fetchAll();

// Newest products, in-stock items first.
$latest = db()->query(
    'SELECT p.*, c.name AS category_name
     FROM products p
     JOIN categories c ON c.id = p.category_id
     ORDER BY p.in_stock DESC, p.id DESC
     LIMIT 8'
)->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-7">
        <h1>Security, networking and power equipment, delivered across Kenya.</h1>
        <p class="lead">
          CCTV cameras, networking components and power backups. Find the product you need,
          then confirm stock and delivery with us.
        </p>

        <?php $q = ''; $search_large = true; require __DIR__ . '/includes/search_form.php'; ?>

        <div class="d-flex flex-wrap gap-2 mt-4">
          <a href="<?= BASE_URL ?>/products.php" class="btn btn-gold btn-lg">Browse all products</a>
          <a href="<?= e(whatsapp_link('Hello, I would like to make an enquiry.')) ?>"
             class="btn btn-outline-light btn-lg" target="_blank" rel="noopener">Chat on WhatsApp</a>
        </div>
      </div>

      <div class="col-lg-5">
        <nav class="category-index" aria-label="Product categories">
          <h2>What we supply</h2>
          <ul>
            <?php foreach ($categories as $cat): ?>
              <li>
                <a href="<?= BASE_URL ?>/products.php?category=<?= e($cat['slug']) ?>">
                  <span class="ci-name"><?= e($cat['name']) ?></span>
                  <span class="ci-count"><?= (int) $cat['product_count'] ?> product<?= (int) $cat['product_count'] === 1 ? '' : 's' ?></span>
                  <?php if (!empty($cat['description'])): ?>
                    <span class="ci-desc"><?= e($cat['description']) ?></span>
                  <?php endif; ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</section>

<section class="facts">
  <div class="container">
    <div class="facts-grid">
      <div class="fact">
        <h2>Countrywide delivery</h2>
        <p>We deliver to customers across Kenya.</p>
      </div>
      <div class="fact">
        <h2>One supplier</h2>
        <p>CCTV, networking and power backup in one place.</p>
      </div>
      <div class="fact">
        <h2>Talk to a person</h2>
        <p>Ask about stock and delivery on WhatsApp or by phone.</p>
      </div>
    </div>
  </div>
</section>

<main>
  <?php if ($latest): ?>
    <section class="section">
      <div class="container">
        <div class="section-head">
          <h2>Latest products</h2>
          <a href="<?= BASE_URL ?>/products.php">View all products</a>
        </div>
        <div class="row g-4">
          <?php foreach ($latest as $p): ?>
            <div class="col-6 col-md-4 col-lg-3">
              <?php require __DIR__ . '/includes/product_card.php'; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="section section-alt">
    <div class="container">
      <div class="section-head"><h2>How to order</h2></div>
      <?php require __DIR__ . '/includes/order_steps.php'; ?>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <?php require __DIR__ . '/includes/cta_band.php'; ?>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>