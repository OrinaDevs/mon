<?php
// Optional inputs: $q (current search text), $search_category (keep a category filter),
// $search_large (bigger box for the home page hero)
?>
<form action="<?= BASE_URL ?>/products.php" method="get" role="search" class="search-form">
  <?php if (!empty($search_category)): ?>
    <input type="hidden" name="category" value="<?= e($search_category) ?>">
  <?php endif; ?>
  <div class="input-group <?= !empty($search_large) ? 'input-group-lg' : '' ?>">
    <input type="search" name="q" class="form-control" maxlength="100"
           placeholder="Search products"
           value="<?= e($q ?? '') ?>" aria-label="Search products">
    <button class="btn btn-gold">Search</button>
  </div>
</form>