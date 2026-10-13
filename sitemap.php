<?php
// The list of pages for search engines. Built from the database, so new products appear by themselves.
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/seo_helpers.php';

$paths = ['/', '/products.php', '/about.php', '/contact.php'];

// Only categories that have at least one product.
$categories = db()->query(
    'SELECT c.slug FROM categories c
     WHERE EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.id)
     ORDER BY c.name'
)->fetchAll();
foreach ($categories as $c) {
    $paths[] = '/products.php?category=' . rawurlencode($c['slug']);
}

foreach (db()->query('SELECT slug FROM products ORDER BY id')->fetchAll() as $p) {
    $paths[] = '/product.php?slug=' . rawurlencode($p['slug']);
}

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($paths as $path) {
    echo '  <url><loc>' . htmlspecialchars(abs_url($path), ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc></url>\n";
}
echo "</urlset>\n";