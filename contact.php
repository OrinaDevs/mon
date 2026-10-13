<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo_helpers.php';

$page_title = 'Contact';
$active_nav = 'contact';
$extra_css = ['inner.css'];
$meta_description = 'Contact Merchant of Nairobi by WhatsApp, phone or email to check stock, price and delivery.';
$canonical_path = '/contact.php';
$schema = [business_schema()];

require __DIR__ . '/includes/header.php';

$banner_title = 'Contact us';
$banner_text  = 'The fastest way to reach us is WhatsApp or a phone call.';
require __DIR__ . '/includes/page_banner.php';
?>

<main>
  <section class="section">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-7">
          <h2 class="h3 mb-3">Reach us</h2>
          <dl class="data-list">
            <div>
              <dt>WhatsApp</dt>
              <dd>
                <a href="<?= e(whatsapp_link('Hello, I would like to make an enquiry.')) ?>"
                   target="_blank" rel="noopener">Start a chat</a>
              </dd>
            </div>
            <div>
              <dt>Phone</dt>
              <dd>
                <?php foreach (SHOP_PHONES as $i => $phone): ?>
                  <?= $i > 0 ? ' / ' : '' ?><a href="tel:<?= e($phone['intl']) ?>"><?= e($phone['display']) ?></a>
                <?php endforeach; ?>
              </dd>
            </div>
            <div><dt>Email</dt><dd><a href="mailto:<?= e(SHOP_EMAIL) ?>"><?= e(SHOP_EMAIL) ?></a></dd></div>
            <div><dt>Location</dt><dd><?= e(SHOP_LOCATION) ?></dd></div>
            <div><dt>Hours</dt><dd><?= e(SHOP_HOURS) ?></dd></div>
          </dl>

          <div class="d-flex flex-wrap gap-2 mt-4">
            <a href="<?= e(whatsapp_link('Hello, I would like to make an enquiry.')) ?>"
               class="btn btn-gold btn-lg" target="_blank" rel="noopener">Chat on WhatsApp</a>
            <?php foreach (SHOP_PHONES as $phone): ?>
              <a href="tel:<?= e($phone['intl']) ?>" class="btn btn-outline-primary btn-lg">Call <?= e($phone['display']) ?></a>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="info-panel">
            <h2>Ordering and delivery</h2>
            <p>
              Found something you like? Open the product and use <em>Enquire on WhatsApp</em>,
              or message us the product name.
            </p>
            <p class="mb-0">
              We deliver countrywide. Include your town or county so we can give you delivery details.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>