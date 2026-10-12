<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/functions.php';

$page_title = 'Contact';
require __DIR__ . '/includes/header.php';
?>

<main class="container py-5" style="max-width: 860px;">
  <h1 class="mb-2">Contact us</h1>
  <p class="text-muted mb-4">The fastest way to reach us is WhatsApp or a phone call.</p>

  <div class="row g-4">
    <div class="col-md-6">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h2 class="h5">Get in touch</h2>
          <ul class="list-unstyled mb-4">
            <li class="mb-2">
              <strong>Phone:</strong>
              <a href="tel:<?= e(SHOP_PHONE_INTL) ?>"><?= e(SHOP_PHONE) ?></a>
            </li>
            <li class="mb-2">
              <strong>Email:</strong>
              <a href="mailto:<?= e(SHOP_EMAIL) ?>"><?= e(SHOP_EMAIL) ?></a>
            </li>
            <li class="mb-2"><strong>Location:</strong> <?= e(SHOP_LOCATION) ?></li>
            <li><strong>Hours:</strong> <?= e(SHOP_HOURS) ?></li>
          </ul>

          <div class="d-flex flex-wrap gap-2">
            <a href="<?= e(whatsapp_link('Hello, I would like to make an enquiry.')) ?>"
               class="btn btn-gold" target="_blank" rel="noopener">Chat on WhatsApp</a>
            <a href="tel:<?= e(SHOP_PHONE_INTL) ?>" class="btn btn-outline-primary">Call us</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h2 class="h5">Ordering and delivery</h2>
          <p>
            Found something you like? Open the product and tap
            <em>Enquire on WhatsApp</em>, or message us the product name.
          </p>
          <p class="mb-0">
            We deliver countrywide. Include your town or county so we can give you
            delivery details.
          </p>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>