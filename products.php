<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$page_title = 'Products';

$categories = db()->query('SELECT name, slug FROM categories ORDER BY name')->fetchAll();

// Optional filter: products.php?category=networking
$active = $_GET['category'] ?? '';

$sql = 'SELECT p.*, c.name AS category_name
        FROM products p
        JOIN categories c ON c.id = p.category_id';
$params = [];

if ($active !== '') {
    $sql .= ' WHERE c.slug = ?';
    $params[] = $active;
}

$sql .= ' ORDER BY p.id DESC';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<main class="container py-5">
  <h1 class="mb-4">Products</h1>

  <div class="category-filter mb-4">
    <a href="<?= BASE_URL ?>/products.php"
       class="btn btn-sm <?= $active === '' ? 'btn-primary' : 'btn-outline-primary' ?>">All</a>
    <?php foreach ($categories as $cat): ?>
      <a href="<?= BASE_URL ?>/products.php?category=<?= e($cat['slug']) ?>"
         class="btn btn-sm <?= $active === $cat['slug'] ? 'btn-primary' : 'btn-outline-primary' ?>">
        <?= e($cat['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if (!$products): ?>
    <p class="text-muted">No products found in this category yet.</p>
  <?php else: ?>
    <div class="row g-4">
        <?php foreach ($products as $p): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <?php require __DIR__ . '/includes/product_card.php'; ?>
            </div>
        <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>