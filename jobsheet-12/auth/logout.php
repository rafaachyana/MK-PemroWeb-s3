<?php
// Logout: hapus seluruh data session dan cookie-nya.
session_start();
if (!empty($_COOKIE['simpus_remember'])) {
    require_once __DIR__ . '/../includes/koneksi.php';
    $stmt = $pdo->prepare('DELETE FROM remember_tokens WHERE token_hash = :hash');
    $stmt->execute([':hash' => hash('sha256', (string) $_COOKIE['simpus_remember'])]);
    setcookie('simpus_remember', '', ['expires' => time() - 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
}
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();
header('Location: login.php?keluar=1');
exit;
