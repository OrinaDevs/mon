<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/auth.php';

require_admin();

$products = db()->query(
    'SELECT p.*, c.name AS category_name
     FROM products p
     JOIN categories c ON c.id = p.category_id
     ORDER BY p.id DESC'
)->fetchAll();

$page_title = 'Products';
require __DIR__ . '/../includes/admin_header.php';
?>

<main class="container pb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Products</h1>
    <a href="<?= BASE_URL ?>/admin/product-add.php" class="btn btn-primary">Add product</a>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:70px;">Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th style="width:150px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $p): ?>
            <tr>
              <td>
                <?php if ($p['image']): ?>
                  <img src="<?= BASE_URL ?>/uploads/products/<?= e($p['image']) ?>"
                       alt="" width="56" height="42" style="object-fit:cover;">
                <?php else: ?>
                  <span class="text-muted small">None</span>
                <?php endif; ?>
              </td>
              <td><?= e($p['name']) ?></td>
              <td><?= e($p['category_name']) ?></td>
              <td><?= e(format_price($p['price'])) ?></td>
              <td>
                <?php if ($p['in_stock']): ?>
                  <span class="badge text-bg-success">In stock</span>
                <?php else: ?>
                  <span class="badge text-bg-secondary">Out</span>
                <?php endif; ?>
              </td>

                <td>
                <div class="d-flex gap-1">
                    <a href="<?= BASE_URL ?>/admin/product-edit.php?id=<?= (int) $p['id'] ?>"
                    class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="post" action="<?= BASE_URL ?>/admin/product-delete.php" class="m-0"
                        onsubmit="return confirm('Delete this product? This cannot be undone.');">
                        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                        <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$products): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">No products yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>