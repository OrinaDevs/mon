<?php // Expects: $p (a product row joined with category_name) ?>
<div class="card product-card h-100">
  <a href="<?= BASE_URL ?>/product.php?slug=<?= e($p['slug']) ?>">
    <?php if ($p['image']): ?>
      <img src="<?= BASE_URL ?>/uploads/products/<?= e($p['image']) ?>" class="product-img" alt="<?= e($p['name']) ?>" loading="lazy">
    <?php else: ?>
      <div class="product-img no-image">No image</div>
    <?php endif; ?>
  </a>

  <div class="card-body">
    <small class="text-muted"><?= e($p['category_name']) ?></small>
    <h2 class="h6 card-title mt-1">
      <a href="<?= BASE_URL ?>/product.php?slug=<?= e($p['slug']) ?>"
         class="stretched-link text-decoration-none text-dark"><?= e($p['name']) ?></a>
    </h2>
    <p class="fw-bold mb-1"><?= e(format_price($p['price'])) ?></p>
    <?php if ($p['in_stock']): ?>
      <span class="badge text-bg-success">In stock</span>
    <?php else: ?>
      <span class="badge text-bg-secondary">Out of stock</span>
    <?php endif; ?>
  </div>
</div>