<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/upload.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $id = (int) ($_POST['id'] ?? 0);

    $stmt = db()->prepare('SELECT image FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if ($product) {
        db()->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
        delete_product_image($product['image']);
        flash_set('success', 'Product deleted.');
    } else {
        flash_set('danger', 'Product not found.');
    }
} else {
    flash_set('danger', 'Could not delete the product. Please try again.');
}

header('Location: ' . BASE_URL . '/admin/products.php');
exit;