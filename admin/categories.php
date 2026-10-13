<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/auth.php';

require_admin();

$errors = [];
$name = '';
$description = '';

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
            $stmt = db()->prepare('SELECT COUNT(*) FROM categories WHERE name = ?');
            $stmt->execute([$name]);
            if ((int) $stmt->fetchColumn() > 0) {
                $errors[] = 'A category with that name already exists.';
            }
        }

        if (mb_strlen($description) > 255) {
            $errors[] = 'The description must be 255 characters or fewer.';
        }

        if (!$errors) {
            db()->prepare('INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)')
                ->execute([$name, unique_category_slug($name), $description !== '' ? $description : null]);
            flash_set('success', 'Category added.');
            header('Location: ' . BASE_URL . '/admin/categories.php');
            exit;
        }
    }
}

$categories = db()->query(
    'SELECT c.*, COUNT(p.id) AS product_count
     FROM categories c
     LEFT JOIN products p ON p.category_id = c.id
     GROUP BY c.id
     ORDER BY c.name'
)->fetchAll();

$page_title = 'Categories';
require __DIR__ . '/../includes/admin_header.php';
?>

<main class="container pb-5">
  <h1 class="h4 mb-3">Categories</h1>

  <?php foreach ($errors as $err): ?>
    <div class="alert alert-danger py-2"><?= e($err) ?></div>
  <?php endforeach; ?>

    <form method="post" class="card card-body shadow-sm mb-4">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="name">New category</label>
                <input id="name" name="name" class="form-control" maxlength="100"
                    placeholder="e.g. Access Control" value="<?= e($name) ?>" required>
            </div>
            <div class="col-md-8">
                <label class="form-label" for="description">Short description (optional)</label>
                <input id="description" name="description" class="form-control" maxlength="255"
                    placeholder="Shown on the home page tile" value="<?= e($description) ?>">
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary">Add category</button>
        </div>
    </form>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Products</th>
            <th style="width:150px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $c): ?>
            <tr>
              <td><?= e($c['name']) ?></td>
              <td><?= (int) $c['product_count'] ?></td>
              <td>
                <div class="d-flex gap-1">
                  <a href="<?= BASE_URL ?>/admin/category-edit.php?id=<?= (int) $c['id'] ?>"
                     class="btn btn-sm btn-outline-primary">Rename</a>

                  <?php if ((int) $c['product_count'] > 0): ?>
                    <button class="btn btn-sm btn-outline-secondary" disabled
                            title="Move or delete its products first">Delete</button>
                  <?php else: ?>
                    <form method="post" action="<?= BASE_URL ?>/admin/category-delete.php" class="m-0"
                          onsubmit="return confirm('Delete this category?');">
                      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                      <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                      <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$categories): ?>
            <tr><td colspan="3" class="text-center text-muted py-4">No categories yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>