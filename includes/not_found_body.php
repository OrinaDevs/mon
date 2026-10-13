<?php
// Optional: $not_found_title, $not_found_text
$q = '';
?>
<main class="container py-5 text-center" style="max-width: 640px;">
  <p class="display-1 fw-bold mb-0" style="color: var(--mon-gold);">404</p>
  <h1 class="h3 mb-3"><?= e($not_found_title ?? 'Page not found') ?></h1>
  <p class="text-muted mb-4">
    <?= e($not_found_text ?? 'The page you are looking for does not exist or may have moved.') ?>
  </p>

  <div class="mb-4"><?php require __DIR__ . '/search_form.php'; ?></div>

  <div class="d-flex flex-wrap justify-content-center gap-2">
    <a href="<?= BASE_URL ?>/" class="btn btn-primary">Go to home page</a>
    <a href="<?= BASE_URL ?>/products.php" class="btn btn-outline-primary">Browse products</a>
    <a href="<?= e(whatsapp_link('Hello, I could not find what I was looking for on your website.')) ?>"
       class="btn btn-gold" target="_blank" rel="noopener">Ask on WhatsApp</a>
  </div>
</main>