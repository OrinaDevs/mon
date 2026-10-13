<?php
// The footer lists categories, so it makes sure the database and helpers are loaded.
if (!function_exists('db')) { require __DIR__ . '/db.php'; }
if (!function_exists('e'))  { require __DIR__ . '/functions.php'; }

try {
    $footer_categories = db()->query('SELECT name, slug FROM categories ORDER BY name')->fetchAll();
} catch (Throwable $ex) {
    $footer_categories = [];   // never break a page because the footer could not load categories
}
?>
<footer class="site-footer mt-auto">
  <div class="container footer-main">
    <div class="row g-5">
      <div class="col-lg-4">
        <a href="<?= BASE_URL ?>/" class="footer-brand d-inline-block mb-3">
          <img src="<?= BASE_URL ?>/assets/img/logo-cropped.png" alt="<?= e(SITE_NAME) ?>">
        </a>
        <p class="mb-0">CCTV cameras, networking components and power backups. Deliveries countrywide.</p>
      </div>

      <div class="col-6 col-lg-2">
        <h2 class="footer-heading">Shop</h2>
        <ul class="footer-list">
          <li><a href="<?= BASE_URL ?>/products.php">All products</a></li>
          <?php foreach ($footer_categories as $cat): ?>
            <li><a href="<?= BASE_URL ?>/products.php?category=<?= e($cat['slug']) ?>"><?= e($cat['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="col-6 col-lg-2">
        <h2 class="footer-heading">Company</h2>
        <ul class="footer-list">
          <li><a href="<?= BASE_URL ?>/">Home</a></li>
          <li><a href="<?= BASE_URL ?>/about.php">About us</a></li>
          <li><a href="<?= BASE_URL ?>/contact.php">Contact</a></li>
        </ul>
      </div>

      <div class="col-lg-4">
        <h2 class="footer-heading">Contact</h2>
        <ul class="footer-list">
          <?php foreach (SHOP_PHONES as $phone): ?>
            <li><a href="tel:<?= e($phone['intl']) ?>"><?= e($phone['display']) ?></a></li>
          <?php endforeach; ?>
          <li><a href="mailto:<?= e(SHOP_EMAIL) ?>"><?= e(SHOP_EMAIL) ?></a></li>
          <li><?= e(SHOP_LOCATION) ?></li>
          <li><?= e(SHOP_HOURS) ?></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="footer-base">
    <div class="container d-flex flex-wrap justify-content-between gap-2">
      <span>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?></span>
      <span>Deliveries countrywide</span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>