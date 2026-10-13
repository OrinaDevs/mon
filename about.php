<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$page_title = 'About us';
$active_nav = 'about';
$extra_css = ['inner.css'];
$meta_description = 'Merchant of Nairobi supplies CCTV cameras, networking components and power backups, with deliveries countrywide.';

$canonical_path = '/about.php';

$categories = db()->query('SELECT name, slug, description FROM categories ORDER BY name')->fetchAll();

require __DIR__ . '/includes/header.php';

$banner_title = 'About ' . SITE_NAME;
$banner_text  = 'Your go-to tech centre for CCTV, networking and power backup equipment.';
require __DIR__ . '/includes/page_banner.php';
?>

<main>
  <section class="section">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-7 prose">
          <h2 class="h3 mb-3">Who we are</h2>
          <p class="lead">
            <?= e(SITE_NAME) ?> supplies CCTV cameras, networking components and power backups
            to homes, shops, offices and institutions.
          </p>
          <p>
            We deal in all types of CCTV cameras, networking components and power backups.
            Browse the products on this website, then message or call us to confirm stock,
            price and delivery before you order.
          </p>
          <p>
            We deliver countrywide, so where you are in Kenya does not limit what you can buy from us.
          </p>
        </div>

        <div class="col-lg-5">
          <h2 class="h5 mb-3">At a glance</h2>
          <dl class="data-list">
            <div><dt>Range</dt><dd>CCTV, networking and power backup</dd></div>
            <div><dt>Delivery</dt><dd>Countrywide</dd></div>
            <div><dt>Location</dt><dd><?= e(SHOP_LOCATION) ?></dd></div>
            <div><dt>Hours</dt><dd><?= e(SHOP_HOURS) ?></dd></div>
            <div>
              <dt>Phone</dt>
              <dd>
                <?php foreach (SHOP_PHONES as $i => $phone): ?>
                  <?= $i > 0 ? ' / ' : '' ?><a href="tel:<?= e($phone['intl']) ?>"><?= e($phone['display']) ?></a>
                <?php endforeach; ?>
              </dd>
            </div>
          </dl>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-alt">
    <div class="container">
      <div class="section-head"><h2>What we supply</h2></div>
      <div class="row g-4">
        <?php foreach ($categories as $cat): ?>
          <div class="col-md-4">
            <div class="supply-item">
              <h3><?= e($cat['name']) ?></h3>
              <?php if (!empty($cat['description'])): ?>
                <p><?= e($cat['description']) ?></p>
              <?php endif; ?>
              <a href="<?= BASE_URL ?>/products.php?category=<?= e($cat['slug']) ?>"
                 aria-label="View <?= e($cat['name']) ?> products">View products</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-head"><h2>How to order</h2></div>
      <?php require __DIR__ . '/includes/order_steps.php'; ?>
    </div>
  </section>

  <section class="section pt-0">
    <div class="container">
      <?php require __DIR__ . '/includes/cta_band.php'; ?>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>