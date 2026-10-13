<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/auth.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $id = (int) ($_POST['id'] ?? 0);

    $stmt = db()->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
    $stmt->execute([$id]);
    $inUse = (int) $stmt->fetchColumn();

    $stmt = db()->prepare('SELECT COUNT(*) FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    $exists = (int) $stmt->fetchColumn() > 0;

    if (!$exists) {
        flash_set('danger', 'Category not found.');
    } elseif ($inUse > 0) {
        // Checked again here, because the button being disabled is not a safeguard.
        flash_set('danger', 'That category still has products. Move or delete them first.');
    } else {
        db()->prepare('DELETE FROM categories WHERE id = ?')->execute([$id]);
        flash_set('success', 'Category deleted.');
    }
} else {
    flash_set('danger', 'Could not delete the category. Please try again.');
}

header('Location: ' . BASE_URL . '/admin/categories.php');
exit;