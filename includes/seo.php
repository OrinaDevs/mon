<?php
/**
 * Prints the title, description, canonical link, robots rule, sharing tags and structured data.
 * Pages set any of these before loading the header (all optional):
 *   $seo_title        full <title>, e.g. 'CCTV Cameras in Kenya | Merchant of Nairobi'
 *   $page_title       used to build a title when $seo_title is not set
 *   $meta_description about 150 characters
 *   $canonical_path   the page's own address without the site folder, e.g. '/about.php'
 *   $robots           'noindex,follow' keeps a page out of search results
 *   $og_image         image for link previews (a path or a full address)
 *   $schema           list of structured-data arrays (see json_ld() in seo_helpers.php)
 */
require_once __DIR__ . '/seo_helpers.php';

$seo_title        = $seo_title ?? (($page_title ?? 'Home') . ' | ' . SITE_NAME);
$meta_description = $meta_description ?? 'CCTV cameras, networking components and power backups. Deliveries countrywide.';
$robots           = $robots ?? 'index,follow';
$og_image         = $og_image ?? '/assets/img/og-default.png';
$og_image_url     = preg_match('#^https?://#', $og_image) ? $og_image : abs_url($og_image);
?>
<title><?= e($seo_title) ?></title>
<meta name="description" content="<?= e($meta_description) ?>">
<meta name="robots" content="<?= e($robots) ?>">
<?php if (!empty($canonical_path)): ?>
<link rel="canonical" href="<?= e(abs_url($canonical_path)) ?>">
<meta property="og:url" content="<?= e(abs_url($canonical_path)) ?>">
<?php endif; ?>
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($seo_title) ?>">
<meta property="og:description" content="<?= e($meta_description) ?>">
<meta property="og:image" content="<?= e($og_image_url) ?>">
<meta name="twitter:card" content="summary_large_image">
<?php foreach ($schema ?? [] as $block): ?>
<?= json_ld($block) ?>
<?php endforeach; ?>