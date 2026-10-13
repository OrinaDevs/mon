<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/auth.php';

require_admin();

$id = (int) ($_GET['id'] ?? 0);

$stmt = db()->prepare('SELECT * FROM categories WHERE id = ?');
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    flash_set('danger', 'Category not found.');
    header('Location: ' . BASE_URL . '/admin/categories.php');
    exit;
}

$errors = [];
$name = $category['name'];
$description = $category['description'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'Session expired. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '') {
            $errors[] = 'Category name is required.';
        } elseif (mb_strlen($name) > 100) {
            $errors[] = 'Category name must be 100 characters or fewer.';
        } elseif (!preg_match('/[a-z0-9]/i', $name)) {
            $errors[] = 'Category name must contain at least one letter or number.';
        } else {
            $stmt = db()->prepare('SELECT COUNT(*) FROM categories WHERE name = ? AND id <> ?');
            $stmt->execute([$name, $id]);
            if ((int) $stmt->fetchColumn() > 0) {
                $errors[] = 'Another category already has that name.';
            }
        }

        if (mb_strlen($description) > 255) {
            $errors[] = 'The description must be 255 characters or fewer.';
        }

        if (!$errors) {
            // The slug is not changed, so existing links keep working.
            db()->prepare('UPDATE categories SET name = ?, description = ? WHERE id = ?')
                ->execute([$name, $description !== '' ? $description : null, $id]);
            flash_set('success', 'Category updated.');
            header('Location: ' . BASE_URL . '/admin/categories.php');
            exit;
        }
    }
}

$page_title = 'Edit category';
require __DIR__ . '/../includes/admin_header.php';
?>

<main class="container pb-5" style="max-width: 520px;">
  <h1 class="h4 mb-3">Edit category</h1>

  <?php foreach ($errors as $err): ?>
    <div class="alert alert-danger py-2"><?= e($err) ?></div>
  <?php endforeach; ?>

  <form method="post" class="card shadow-sm card-body">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

    <div class="mb-3">
      <label class="form-label" for="name">Name</label>
      <input id="name" name="name" class="form-control" maxlength="100"
             value="<?= e($name) ?>" required autofocus>
    </div>

    <div class="mb-3">
      <label class="form-label" for="description">Short description</label>
      <input id="description" name="description" class="form-control" maxlength="255"
             value="<?= e($description) ?>">
      <div class="form-text">Shown on the home page tile. Optional.</div>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary">Save</button>
      <a href="<?= BASE_URL ?>/admin/categories.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
</main>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>