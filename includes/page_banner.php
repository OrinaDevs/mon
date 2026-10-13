<?php // Expects: $banner_title, optional $banner_text ?>
<section class="page-banner">
  <div class="container">
    <h1><?= e($banner_title ?? '') ?></h1>
    <?php if (!empty($banner_text)): ?>
      <p><?= e($banner_text) ?></p>
    <?php endif; ?>
  </div>
</section>