<?php
// ---- Settings ---------------------------------------------------------
const MAX_IP_FAILS          = 5;         // failed logins per IP...
const MAX_USER_FAILS        = 10;        // ...and per username
const LOCKOUT_MINUTES       = 15;        // ...within this window
const SESSION_IDLE_SECONDS  = 30 * 60;   // log out after 30 min of inactivity
const SESSION_MAX_SECONDS   = 8 * 3600;  // log out 8 hours after login

// ---- Sessions ---------------------------------------------------------
function start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.use_strict_mode', '1');
        ini_set('session.gc_maxlifetime', (string) SESSION_IDLE_SECONDS);
        session_set_cookie_params([
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => !empty($_SERVER['HTTPS']),
        ]);
        session_start();
    }
}

function is_admin(): bool
{
    start_session();

    if (empty($_SESSION['admin_id'])) {
        return false;
    }

    $now  = time();
    $idle = $now - ($_SESSION['last_activity'] ?? 0);
    $age  = $now - ($_SESSION['login_time'] ?? 0);

    if ($idle > SESSION_IDLE_SECONDS || $age > SESSION_MAX_SECONDS) {
        unset($_SESSION['admin_id'], $_SESSION['login_time'], $_SESSION['last_activity']);
        session_regenerate_id(true);
        flash_set('warning', 'Your session expired. Please log in again.');
        return false;
    }

    $_SESSION['last_activity'] = $now;   // activity keeps the session alive
    return true;
}

// Put this at the top of every admin page.
function require_admin(): void
{
    if (!is_admin()) {
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
}

// Call after a successful password check.
function admin_log_in(int $adminId): void
{
    start_session();
    session_regenerate_id(true);   // prevents session fixation
    $_SESSION['admin_id']      = $adminId;
    $_SESSION['login_time']    = time();
    $_SESSION['last_activity'] = time();
}

// ---- Login attempt limiting -------------------------------------------
// Needs includes/db.php to be loaded first.
function client_ip(): string
{
    // REMOTE_ADDR only: headers like X-Forwarded-For can be faked by the visitor.
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

function login_is_locked(string $username): bool
{
    $window = 'attempted_at > (NOW() - INTERVAL ' . LOCKOUT_MINUTES . ' MINUTE)';

    $stmt = db()->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND $window");
    $stmt->execute([client_ip()]);
    if ((int) $stmt->fetchColumn() >= MAX_IP_FAILS) {
        return true;
    }

    $stmt = db()->prepare("SELECT COUNT(*) FROM login_attempts WHERE username = ? AND $window");
    $stmt->execute([substr($username, 0, 50)]);
    return (int) $stmt->fetchColumn() >= MAX_USER_FAILS;
}

function login_record_failure(string $username): void
{
    db()->prepare('INSERT INTO login_attempts (ip, username) VALUES (?, ?)')
        ->execute([client_ip(), substr($username, 0, 50)]);

    // Housekeeping: drop records older than a day.
    db()->exec('DELETE FROM login_attempts WHERE attempted_at < (NOW() - INTERVAL 1 DAY)');
}

function login_clear_attempts(string $username): void
{
    db()->prepare('DELETE FROM login_attempts WHERE ip = ? OR username = ?')
        ->execute([client_ip(), substr($username, 0, 50)]);
}

// ---- CSRF -------------------------------------------------------------
function csrf_token(): string
{
    start_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(): bool
{
    start_session();
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

// ---- Flash messages ---------------------------------------------------
function flash_set(string $type, string $message): void
{
    start_session();
    $_SESSION['flash'] = [$type, $message];
}

function flash_get(): ?array
{
    start_session();
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}