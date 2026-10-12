<?php
// Expects: $old, $categories, $submit_label, $current_image (string|null)
?>
<form method="post" enctype="multipart/form-data" class="card shadow-sm card-body">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

  <div class="mb-3">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" value="<?= e($old['name']) ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Category</label>
    <select name="category_id" class="form-select" required>
      <option value="">Choose...</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= (int) $c['id'] ?>" <?= (int) $old['category_id'] === (int) $c['id'] ? 'selected' : '' ?>>
          <?= e($c['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="4" class="form-control"><?= e($old['description']) ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Price (KES)</label>
    <input name="price" type="number" step="0.01" min="0" class="form-control"
           value="<?= e((string) $old['price']) ?>">
    <div class="form-text">Leave empty to show "Call for price".</div>
  </div>

  <div class="mb-3">
    <label class="form-label">Image</label>
    <?php if ($current_image): ?>
      <div class="mb-2">
        <img src="<?= BASE_URL ?>/uploads/products/<?= e($current_image) ?>"
             alt="" width="120" style="object-fit:cover;">
      </div>
    <?php endif; ?>
    <input name="image" type="file" class="form-control" accept="image/jpeg,image/png,image/webp">
    <div class="form-text">
      JPG, PNG or WebP, up to 2 MB.
      <?= $current_image ? 'Choosing a new file replaces the current image.' : '' ?>
    </div>
    <?php if ($current_image): ?>
      <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image">
        <label class="form-check-label" for="remove_image">Remove current image</label>
      </div>
    <?php endif; ?>
  </div>

  <div class="form-check mb-4">
    <input class="form-check-input" type="checkbox" name="in_stock" id="in_stock"
           <?= $old['in_stock'] ? 'checked' : '' ?>>
    <label class="form-check-label" for="in_stock">In stock</label>
  </div>

  <div class="d-flex gap-2">
    <button class="btn btn-primary"><?= e($submit_label) ?></button>
    <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-outline-secondary">Cancel</a>
  </div>
</form>