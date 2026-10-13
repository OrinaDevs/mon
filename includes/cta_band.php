<?php
// Optional: $cta_title, $cta_text
$cta_title = $cta_title ?? 'Looking for something specific?';
$cta_text  = $cta_text  ?? 'Tell us what you need and we will confirm availability and delivery.';
?>
<div class="cta-band">
  <div>
    <h2><?= e($cta_title) ?></h2>
    <p><?= e($cta_text) ?></p>
  </div>
  <div class="d-flex flex-wrap gap-2">
    <a href="<?= e(whatsapp_link('Hello, I am looking for a product and would like to check availability.')) ?>"
       class="btn btn-gold btn-lg" target="_blank" rel="noopener">Chat on WhatsApp</a>
    <a href="tel:<?= e(SHOP_PHONES[0]['intl']) ?>" class="btn btn-outline-light btn-lg">Call <?= e(SHOP_PHONES[0]['display']) ?></a>
  </div>
</div>