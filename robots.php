<?php
// Served as /robots.txt (see .htaccess). Tells search engines what to skip and where the sitemap is.
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/seo_helpers.php';

header('Content-Type: text/plain; charset=utf-8');
echo "User-agent: *\n";
echo 'Disallow: ' . BASE_URL . "/admin/\n";
echo "\n";
echo 'Sitemap: ' . abs_url('/sitemap.xml') . "\n";