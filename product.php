<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo_helpers.php';

// Only accept plain text from the address bar.
$slug = is_string($_GET['slug'] ?? null) ? $_GET['slug'] : '';
$stmt = db()->prepare(
    'SELECT p.*, c.name AS category_name, c.slug AS category_slug
     FROM products p JOIN categories c ON c.id = p.category_id WHERE p.slug = ?'
);
$stmt->execute([$slug]);
$p = $stmt->fetch();

if (!$p) {
    http_response_code(404);
    $page_title = 'Product not found';
    $not_found_title = 'Product not found';
    $not_found_text = 'We could not find that product. It may have been removed.';
    require __DIR__ . '/includes/header.php';
    require __DIR__ . '/includes/not_found_body.php';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$page_title = $p['name'];
$active_nav = 'products';
$wa_message = 'Hello, I am interested in the ' . $p['name'] . '. Is it available?';

// ---- Search engine and sharing tags --------------------------------------
$product_path   = '/product.php?slug=' . rawurlencode($p['slug']);
$canonical_path = $product_path;
$seo_title      = $p['name'] . ' | ' . $p['category_name'] . ' | ' . SITE_NAME;

$summary = $p['name']
         . ($p['price'] !== null ? ' - ' . format_price($p['price']) : '')
         . '. '
         . ($p['description'] ?: 'Available from ' . SITE_NAME . '. Confirm stock and delivery on WhatsApp.');
$meta_description = seo_excerpt($summary);

// WhatsApp and Facebook show this picture when someone shares the link.
if ($p['image']) {
    $og_image = '/uploads/products/' . rawurlencode($p['image']);
}

$product_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => $p['name'],
    'description' => seo_excerpt($p['description'] ?: $p['name'] . ' from ' . SITE_NAME, 500),
    'category'    => $p['category_name'],
    'url'         => abs_url($product_path),
];
if ($p['image']) {
    $product_schema['image'] = [abs_url('/uploads/products/' . rawurlencode($p['image']))];
}
// An offer needs a price, so products set to "Call for price" are described without one.
if ($p['price'] !== null) {
    $product_schema['offers'] = [
        '@type'         => 'Offer',
        'url'           => abs_url($product_path),
        'priceCurrency' => 'KES',
        'price'         => number_format((float) $p['price'], 2, '.', ''),
        'availability'  => $p['in_stock'] ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        'seller'        => ['@type' => 'Organization', 'name' => SITE_NAME],
    ];
}

$breadcrumb_schema = [
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Products',
         'item' => abs_url('/products.php')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $p['category_name'],
         'item' => abs_url('/products.php?category=' . rawurlencode($p['category_slug']))],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $p['name'],
         'item' => abs_url($product_path)],
    ],
];
$schema = [$breadcrumb_schema, $product_schema];

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
        <img src="<?= BASE_URL ?>/uploads/products/<?= e($p['image']) ?>" class="img-fluid rounded border" alt="<?= e($p['name']) ?>">
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
        <a href="<?= e(whatsapp_link($wa_message)) ?>" class="btn btn-gold btn-lg" target="_blank" rel="noopener">
          <?= $p['in_stock'] ? 'Enquire on WhatsApp' : 'Ask about availability' ?>
        </a>
        <a href="<?= BASE_URL ?>/products.php" class="btn btn-outline-primary btn-lg">Back to products</a>
      </div>
      <p class="text-muted small mt-4 mb-0">Deliveries done countrywide.</p>
    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>