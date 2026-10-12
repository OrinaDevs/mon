<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$page_title = 'Home';
$extra_css = ['home.css'];

$categories = db()->query('SELECT name, slug FROM categories ORDER BY name')->fetchAll();

// Short blurbs for the tiles. New categories without a blurb simply show none.
$blurbs = [
    'cctv-cameras' => 'Cameras and recorders for homes, shops and offices.',
    'networking'   => 'Switches, cabling and other networking components.',
    'power-backup' => 'Keep your equipment running when the power goes.',
];

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
    <h1 class="display-5 fw-bold"><?= e(SITE_TAGLINE) ?></h1>
    <p class="lead mt-3">
      CCTV cameras, networking components and power backups, with deliveries countrywide.
    </p>
    <div class="d-flex flex-wrap gap-2 mt-4">
      <a href="<?= BASE_URL ?>/products.php" class="btn btn-gold btn-lg">Browse products</a>
      <a href="<?= e(whatsapp_link('Hello, I would like to make an enquiry.')) ?>"
         class="btn btn-outline-light btn-lg" target="_blank" rel="noopener">Chat on WhatsApp</a>
    </div>
  </div>
</section>

<main>
  <section class="container py-5">
    <h2 class="h3 mb-4">Shop by category</h2>
    <div class="row g-4">
      <?php foreach ($categories as $cat): ?>
        <div class="col-12 col-md-4">
          <a href="<?= BASE_URL ?>/products.php?category=<?= e($cat['slug']) ?>" class="category-tile">
            <h3 class="h5"><?= e($cat['name']) ?></h3>
            <?php if (!empty($blurbs[$cat['slug']])): ?>
              <p class="mb-0 text-muted"><?= e($blurbs[$cat['slug']]) ?></p>
            <?php endif; ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <?php if ($latest): ?>
    <section class="container pb-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Latest products</h2>
        <a href="<?= BASE_URL ?>/products.php">View all</a>
      </div>
      <div class="row g-4">
        <?php foreach ($latest as $p): ?>
          <div class="col-6 col-md-4 col-lg-3">
            <?php require __DIR__ . '/includes/product_card.php'; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <section class="container pb-5">
    <div class="cta-band p-4 p-md-5 text-center">
      <h2 class="h3">Can't find what you need?</h2>
      <p class="mb-4">Tell us what you're looking for and we'll let you know what's available.</p>
      <a href="<?= e(whatsapp_link('Hello, I am looking for a product that I could not find on your website.')) ?>"
         class="btn btn-gold btn-lg" target="_blank" rel="noopener">Ask on WhatsApp</a>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>