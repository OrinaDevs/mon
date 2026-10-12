<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title><?= e($page_title ?? 'Admin') ?> | <?= SITE_NAME ?> Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/custom.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-dark mb-4" style="background: var(--mon-green);">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>/admin/"><?= SITE_NAME ?> Admin</a>
    <?php if (is_admin()): ?>
      <form method="post" action="<?= BASE_URL ?>/admin/logout.php" class="m-0">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <button class="btn btn-sm btn-outline-light">Log out</button>
      </form>
    <?php endif; ?>
  </div>
</nav>

<?php if ($flash = flash_get()): ?>
  <div class="container">
    <div class="alert alert-<?= e($flash[0]) ?>"><?= e($flash[1]) ?></div>
  </div>
<?php endif; ?>