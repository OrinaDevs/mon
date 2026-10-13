<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/pagination.php';
require_once __DIR__ . '/includes/seo_helpers.php';

const PRODUCTS_PER_PAGE = 12;

$page_title = 'Products';
$active_nav = 'products';

$categories = db()->query('SELECT name, slug, description FROM categories ORDER BY name')->fetchAll();

// Only accept plain text from the address bar (a ?q[]=x style value would otherwise cause an error).
$active = is_string($_GET['category'] ?? null) ? $_GET['category'] : '';
$q      = is_string($_GET['q'] ?? null) ? mb_substr(trim($_GET['q']), 0, 100) : '';
$page   = max(1, (int) ($_GET['page'] ?? 1));

// Which category is being viewed, if any. An unknown one is a 404, not an empty page.
$active_category = null;
foreach ($categories as $c) {
    if ($c['slug'] === $active) {
        $active_category = $c;
        break;
    }
}

if ($active !== '' && $active_category === null) {
    http_response_code(404);
    $page_title = 'Category not found';
    $not_found_title = 'Category not found';
    $not_found_text = 'We could not find that category. Try the full product list.';
    require __DIR__ . '/includes/header.php';
    require __DIR__ . '/includes/not_found_body.php';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$where = [];
$params = [];

if ($active !== '') {
    $where[] = 'c.slug = ?';
    $params[] = $active;
}

// Every word typed must appear in the name, description or category name.
if ($q !== '') {
    $words = array_slice(preg_split('/\s+/u', $q, -1, PREG_SPLIT_NO_EMPTY), 0, 5);
    foreach ($words as $word) {
        // Escape % and _ so they are searched literally.
        $like = '%' . addcslashes($word, '%_\\') . '%';
        $where[] = '(p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?)';
        array_push($params, $like, $like, $like);
    }
}

$from = ' FROM products p JOIN categories c ON c.id = p.category_id'
      . ($where ? ' WHERE ' . implode(' AND ', $where) : '');

// 1) How many products match in total?
$stmt = db()->prepare('SELECT COUNT(*)' . $from);
$stmt->execute($params);
$total = (int) $stmt->fetchColumn();

$pages = max(1, (int) ceil($total / PRODUCTS_PER_PAGE));
$page = min($page, $pages);                       // a page past the end shows the last page
$offset = ($page - 1) * PRODUCTS_PER_PAGE;

// 2) Fetch just this page. Both numbers are integers we calculated, not user text.
$stmt = db()->prepare(
    'SELECT p.*, c.name AS category_name' . $from .
    ' ORDER BY p.id DESC LIMIT ' . PRODUCTS_PER_PAGE . ' OFFSET ' . $offset
);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Builds a products.php address that keeps the category and search text.
function products_url(string $category, string $q, int $page = 1): string
{
    $query = array_filter(
        ['category' => $category, 'q' => $q, 'page' => $page > 1 ? $page : ''],
        fn($v) => $v !== ''
    );
    return BASE_URL . '/products.php' . ($query ? '?' . http_build_query($query) : '');
}

$search_category = $active;

// ---- Search engine and sharing tags --------------------------------------
$page_suffix = $page > 1 ? ' - Page ' . $page : '';

if ($active_category) {
    $seo_title = $active_category['name'] . ' in Kenya' . $page_suffix . ' | ' . SITE_NAME;
    $meta_description = !empty($active_category['description'])
        ? seo_excerpt($active_category['name'] . ': ' . $active_category['description'] . ' Deliveries countrywide.')
        : seo_excerpt('Browse ' . $active_category['name'] . ' from ' . SITE_NAME . '. Confirm stock and delivery on WhatsApp or by phone.');
} else {
    $seo_title = 'CCTV, Networking and Power Backup Products' . $page_suffix . ' | ' . SITE_NAME;
    $meta_description = 'Browse CCTV cameras, networking components and power backups from ' . SITE_NAME
                      . '. Search by product or filter by category. Deliveries countrywide.';
}

if ($q !== '') {
    // Search results are different for every search, so keep them out of Google.
    $seo_title = 'Search: ' . $q . ' | ' . SITE_NAME;
    $robots = 'noindex,follow';
} elseif ($total === 0) {
    $robots = 'noindex,follow';            // an empty category is not worth indexing
} else {
    $canonical_path = substr(products_url($active, '', $page), strlen(BASE_URL));
}

require __DIR__ . '/includes/header.php';

$banner_title = $active_category ? $active_category['name'] : 'Products';
$banner_text  = ($active_category && !empty($active_category['description']))
    ? $active_category['description']
    : 'CCTV cameras, networking components and power backups. Search or filter by category.';
require __DIR__ . '/includes/page_banner.php';
?>

<main class="container section">
  <div class="mb-4">
    <?php require __DIR__ . '/includes/search_form.php'; ?>
  </div>

  <div class="category-filter mb-4">
    <a href="<?= e(products_url('', $q)) ?>"
       class="btn btn-sm <?= $active === '' ? 'btn-primary' : 'btn-outline-primary' ?>">All</a>
    <?php foreach ($categories as $cat): ?>
      <a href="<?= e(products_url($cat['slug'], $q)) ?>"
         class="btn btn-sm <?= $active === $cat['slug'] ? 'btn-primary' : 'btn-outline-primary' ?>">
        <?= e($cat['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ($q !== ''): ?>
    <p class="text-muted">
      <?= $total ?> result<?= $total === 1 ? '' : 's' ?> for
      <strong>&ldquo;<?= e($q) ?>&rdquo;</strong>
      <a href="<?= e(products_url($active, '')) ?>" class="ms-2">Clear search</a>
    </p>
  <?php elseif ($total > 0): ?>
    <p class="text-muted small">
      Showing <?= $offset + 1 ?>&ndash;<?= $offset + count($products) ?> of <?= $total ?> products
    </p>
  <?php endif; ?>

  <?php if (!$products): ?>
    <div class="alert alert-light border">
      <?php if ($q !== ''): ?>
        <p class="mb-2">No products matched &ldquo;<?= e($q) ?>&rdquo;. Try fewer or different words.</p>
        <a href="<?= e(whatsapp_link('Hello, I am looking for: ' . $q)) ?>"
           class="btn btn-gold btn-sm" target="_blank" rel="noopener">Ask us on WhatsApp</a>
      <?php else: ?>
        <p class="mb-0">No products found in this category yet.</p>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($products as $p): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <?php require __DIR__ . '/includes/product_card.php'; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <?= pagination_links($page, $pages, fn(int $n) => products_url($active, $q, $n)) ?>
  <?php endif; ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>