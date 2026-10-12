<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';

$stmt = db()->prepare(
    'SELECT p.*, c.name AS category_name, c.slug AS category_slug
     FROM products p
     JOIN categories c ON c.id = p.category_id
     WHERE p.slug = ?'
);
$stmt->execute([$slug]);
$p = $stmt->fetch();

if (!$p) {
    http_response_code(404);
    $page_title = 'Product not found';
    require __DIR__ . '/includes/header.php';
    echo '<main class="container py-5"><h1 class="h3">Product not found</h1>'
       . '<p><a href="' . BASE_URL . '/products.php">Back to products</a></p></main>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$page_title = $p['name'];
$wa_message = 'Hello, I am interested in the ' . $p['name'] . '. Is it available?';

require __DIR__ . '/includes/header.php';
?>

<main class="container py-5">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/products.php">Products</a></li>
      <li class="breadcrumb-item">
        <a href="<?= BASE_URL ?>/products.php?category=<?= e($p['category_slug']) ?>"><?= e($p['category_name']) ?></a>
      </li>
      <li class="breadcrumb-item active" aria-current="page"><?= e($p['name']) ?></li>
    </ol>
  </nav>

  <div class="row g-5">
    <div class="col-md-6">
      <?php if ($p['image']): ?>
        <img src="<?= BASE_URL ?>/uploads/products/<?= e($p['image']) ?>"
             class="img-fluid rounded border" alt="<?= e($p['name']) ?>">
      <?php else: ?>
        <div class="product-img no-image rounded border">No image</div>
      <?php endif; ?>
    </div>

    <div class="col-md-6">
      <small class="text-muted"><?= e($p['category_name']) ?></small>
      <h1 class="h2 mt-1"><?= e($p['name']) ?></h1>

      <p class="fs-4 fw-bold mb-2"><?= e(format_price($p['price'])) ?></p>

      <?php if ($p['in_stock']): ?>
        <span class="badge text-bg-success mb-3">In stock</span>
      <?php else: ?>
        <span class="badge text-bg-secondary mb-3">Out of stock</span>
      <?php endif; ?>

      <?php if ($p['description']): ?>
        <p class="mt-2"><?= nl2br(e($p['description'])) ?></p>
      <?php endif; ?>

      <div class="d-flex flex-wrap gap-2 mt-4">
        <a href="<?= e(whatsapp_link($wa_message)) ?>" class="btn btn-gold btn-lg"
           target="_blank" rel="noopener">
          <?= $p['in_stock'] ? 'Enquire on WhatsApp' : 'Ask about availability' ?>
        </a>
        <a href="<?= BASE_URL ?>/products.php" class="btn btn-outline-primary btn-lg">Back to products</a>
      </div>

      <p class="text-muted small mt-4 mb-0">Deliveries done countrywide.</p>
    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>