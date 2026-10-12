<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/functions.php';

$page_title = 'About us';
require __DIR__ . '/includes/header.php';
?>

<main class="container py-5" style="max-width: 860px;">
  <h1 class="mb-4">About <?= e(SITE_NAME) ?></h1>

  <p class="lead">
    <?= e(SITE_NAME) ?> is your go-to tech centre for security and power equipment.
  </p>

  <p>
    We deal in all types of CCTV cameras, networking components and power backups,
    for homes, shops, offices and institutions.
  </p>

  <h2 class="h4 mt-5">What we offer</h2>
  <div class="row g-4 mt-1">
    <div class="col-md-4">
      <h3 class="h6 text-uppercase" style="color: var(--mon-green);">CCTV</h3>
      <p class="mb-0">Cameras, recorders and accessories to keep your property monitored.</p>
    </div>
    <div class="col-md-4">
      <h3 class="h6 text-uppercase" style="color: var(--mon-green);">Networking</h3>
      <p class="mb-0">Switches, cabling and other components to build a reliable network.</p>
    </div>
    <div class="col-md-4">
      <h3 class="h6 text-uppercase" style="color: var(--mon-green);">Power backup</h3>
      <p class="mb-0">Backup solutions that keep your equipment running through outages.</p>
    </div>
  </div>

  <h2 class="h4 mt-5">Delivery countrywide</h2>
  <p>
    We deliver across Kenya. Send us a message with the products you need and your
    location, and we will confirm availability and delivery details.
  </p>

  <div class="mt-4 d-flex flex-wrap gap-2">
    <a href="<?= BASE_URL ?>/products.php" class="btn btn-primary">Browse products</a>
    <a href="<?= BASE_URL ?>/contact.php" class="btn btn-outline-primary">Contact us</a>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>