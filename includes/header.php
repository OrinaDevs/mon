<?php
// Expects (all optional): $page_title, $active_nav, $extra_css (array), plus the SEO
// variables described at the top of seo.php.
require_once __DIR__ . '/seo_helpers.php';
$active_nav = $active_nav ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php require __DIR__ . '/head_icons.php'; ?>
  <?php require __DIR__ . '/seo.php'; ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/tokens.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components.css">
  <?php foreach ($extra_css ?? [] as $css): ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/<?= e($css) ?>">
  <?php endforeach; ?>
</head>
<body class="d-flex flex-column min-vh-100">

<div class="utility-bar">
  <div class="container">
    <ul class="utility-list">
      <?php foreach (SHOP_PHONES as $phone): ?>
        <li><a href="tel:<?= e($phone['intl']) ?>"><?= e($phone['display']) ?></a></li>
      <?php endforeach; ?>
      <li class="d-none d-md-block"><a href="mailto:<?= e(SHOP_EMAIL) ?>"><?= e(SHOP_EMAIL) ?></a></li>
    </ul>
    <span class="d-none d-lg-inline"><?= e(SHOP_HOURS) ?></span>
  </div>
</div>

<header class="site-header">
  <nav class="navbar navbar-expand-lg" aria-label="Main">
    <div class="container">
      <a class="navbar-brand" href="<?= BASE_URL ?>/">
        <img src="<?= BASE_URL ?>/assets/img/logo-cropped.png" alt="<?= e(SITE_NAME) ?>">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
              data-bs-target="#mainNav" aria-controls="mainNav"
              aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link <?= $active_nav === 'home' ? 'active' : '' ?>" href="<?= BASE_URL ?>/">Home</a></li>
          <li class="nav-item"><a class="nav-link <?= $active_nav === 'products' ? 'active' : '' ?>" href="<?= BASE_URL ?>/products.php">Products</a></li>
          <li class="nav-item"><a class="nav-link <?= $active_nav === 'about' ? 'active' : '' ?>" href="<?= BASE_URL ?>/about.php">About</a></li>
          <li class="nav-item"><a class="nav-link <?= $active_nav === 'contact' ? 'active' : '' ?>" href="<?= BASE_URL ?>/contact.php">Contact</a></li>
          <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
            <a class="btn btn-gold btn-sm" href="<?= e(whatsapp_link('Hello, I would like to make an enquiry.')) ?>"
               target="_blank" rel="noopener">Chat on WhatsApp</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>