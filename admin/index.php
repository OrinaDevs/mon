<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/auth.php';

require_admin();

$total = (int) db()->query('SELECT COUNT(*) FROM products')->fetchColumn();

$page_title = 'Dashboard';
require __DIR__ . '/../includes/admin_header.php';
?>

<main class="container">
  <h1 class="h4 mb-4">Dashboard</h1>
  <div class="card shadow-sm mb-4" style="max-width: 280px;">
    <div class="card-body">
      <div class="text-muted small">Products</div>
      <div class="display-6"><?= $total ?></div>
    </div>
  </div>
  <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-primary">Manage products</a>
  <a href="<?= BASE_URL ?>/admin/product-add.php" class="btn btn-gold">Add product</a>
</main>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>