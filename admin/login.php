<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/auth.php';

start_session();

if (is_admin()) {
    header('Location: ' . BASE_URL . '/admin/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!csrf_check()) {
        $error = 'Session expired. Please try again.';
    } elseif (login_is_locked($username)) {
        $error = 'Too many failed attempts. Please try again in ' . LOCKOUT_MINUTES . ' minutes.';
    } else {
        $stmt = db()->prepare('SELECT id, password_hash FROM admins WHERE username = ?');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            login_clear_attempts($username);
            admin_log_in((int) $admin['id']);
            header('Location: ' . BASE_URL . '/admin/');
            exit;
        }

        login_record_failure($username);
        // Same message for wrong username or wrong password.
        $error = 'Invalid username or password.';
    }
}

$page_title = 'Login';
require __DIR__ . '/../includes/admin_header.php';
?>

<main class="container" style="max-width: 420px;">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h1 class="h4 mb-3">Admin login</h1>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input name="password" type="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Log in</button>
      </form>
    </div>
  </div>
</main>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>