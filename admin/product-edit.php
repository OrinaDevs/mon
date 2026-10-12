<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/upload.php';

require_admin();

$id = (int) ($_GET['id'] ?? 0);

$stmt = db()->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    flash_set('danger', 'Product not found.');
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit;
}

$categories = db()->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$errors = [];

$old = [
    'name'        => $product['name'],
    'category_id' => $product['category_id'],
    'description' => $product['description'] ?? '',
    'price'       => $product['price'] ?? '',
    'in_stock'    => (int) $product['in_stock'],
];

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

        $newImage = null;
        if (!$errors) {
            [$newImage, $imageError] = save_product_image($_FILES['image'] ?? ['error' => UPLOAD_ERR_NO_FILE]);
            if ($imageError) {
                $errors[] = $imageError;
            }
        }

        if (!$errors) {
            $image = $product['image'];
            if ($newImage) {
                $image = $newImage;
            } elseif (isset($_POST['remove_image'])) {
                $image = null;
            }

            // The slug stays the same on edit so existing links keep working.
            $stmt = db()->prepare(
                'UPDATE products
                 SET category_id = ?, name = ?, description = ?, price = ?, image = ?, in_stock = ?
                 WHERE id = ?'
            );
            $stmt->execute([
                $old['category_id'],
                $old['name'],
                $old['description'] !== '' ? $old['description'] : null,
                $old['price'] !== '' ? $old['price'] : null,
                $image,
                $old['in_stock'],
                $id,
            ]);

            // Remove the old file only after the database update succeeded.
            if ($product['image'] && $image !== $product['image']) {
                delete_product_image($product['image']);
            }

            flash_set('success', 'Product updated.');
            header('Location: ' . BASE_URL . '/admin/products.php');
            exit;
        }
    }
}

$page_title = 'Edit product';
$submit_label = 'Save changes';
$current_image = $product['image'];
require __DIR__ . '/../includes/admin_header.php';
?>

<main class="container pb-5" style="max-width: 720px;">
  <h1 class="h4 mb-3">Edit product</h1>

  <?php foreach ($errors as $err): ?>
    <div class="alert alert-danger py-2"><?= e($err) ?></div>
  <?php endforeach; ?>

  <?php require __DIR__ . '/../includes/product_form.php'; ?>
</main>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>