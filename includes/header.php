<!DOCTYPE html>
<html lan="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= htmlspecialchars($page_title ?? 'Home') ?> | <?= SITE_NAME ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/custom.css">
        <?php foreach ($extra_css ?? [] as $css): ?>
            <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/<?= e($css) ?>">
        <?php endforeach; ?>
    </head>

    <body class="d-flex flex-column min-vh-100">

        <header class="site-header bg-white">
            <nav class="navbar navbar-expand-md">
                <div class="container">
                    <a class="navbar-brand" href="<?= BASE_URL ?>/">
                        <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="<?= SITE_NAME ?>">
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#mainNav" aria-controls="mainNav"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="mainNav">
                        <ul class="navbar-nav ms-auto">
                            <?php $active_nav = $active_nav ?? ''; ?>
                            <li class="nav-item"><a class="nav-link <?= $active_nav === 'home' ? 'active' : '' ?>" href="<?= BASE_URL ?>/">Home</a></li>
                            <li class="nav-item"><a class="nav-link <?= $active_nav === 'products' ? 'active' : '' ?>" href="<?= BASE_URL ?>/products.php">Products</a></li>
                            <li class="nav-item"><a class="nav-link <?= $active_nav === 'about' ? 'active' : '' ?>" href="<?= BASE_URL ?>/about.php">About</a></li>
                            <li class="nav-item"><a class="nav-link <?= $active_nav === 'contact' ? 'active' : '' ?>" href="<?= BASE_URL ?>/contact.php">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

