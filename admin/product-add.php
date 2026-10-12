<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/upload.php';

require_admin();

$categories = db()->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$errors = [];
$old = ['name' => '', 'category_id' => '', 'description' => '', 'price' => '', 'in_stock' => 1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'Session expired. Please try again.';
    } else {
        $old = [
            'name'        => trim($_POST['name'] ?? ''),
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'description' => trim($_POST['description'] ?? ''),
            'price'       => trim($_POST['price'] ?? ''),
            'in_stock'    => isset($_POST['in_stock']) ? 1 : 0,
        ];

        $validIds = array_map('intval', array_column($categories, 'id'));

        if ($old['name'] === '') {
            $errors[] = 'Product name is required.';
        }
        if (!in_array($old['category_id'], $validIds, true)) {
            $errors[] = 'Choose a category.';
        }
        if ($old['price'] !== '' && (!is_numeric($old['price']) || (float) $old['price'] < 0)) {
            $errors[] = 'Price must be a positive number, or leave it empty.';
        }

        // Only save the image once everything else is valid.
        $image = null;
        if (!$errors) {
            [$image, $imageError] = save_product_image($_FILES['image'] ?? ['error' => UPLOAD_ERR_NO_FILE]);
            if ($imageError) {
                $errors[] = $imageError;
            }
        }

        if (!$errors) {
            $stmt = db()->prepare(
                'INSERT INTO products (category_id, name, slug, description, price, image, in_stock)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $old['category_id'],
                $old['name'],
                unique_product_slug($old['name']),
                $old['description'] !== '' ? $old['description'] : null,
                $old['price'] !== '' ? $old['price'] : null,
                $image,
                $old['in_stock'],
            ]);

            flash_set('success', 'Product added.');
            header('Location: ' . BASE_URL . '/admin/products.php');
            exit;
        }
    }
}

$page_title = 'Add product';
$submit_label = 'Save product';
$current_image = null;
require __DIR__ . '/../includes/admin_header.php';
?>

<main class="container pb-5" style="max-width: 720px;">
  <h1 class="h4 mb-3">Add product</h1>

  <?php foreach ($errors as $err): ?>
    <div class="alert alert-danger py-2"><?= e($err) ?></div>
  <?php endforeach; ?>

  <?php require __DIR__ . '/../includes/product_form.php'; ?>
</main>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>