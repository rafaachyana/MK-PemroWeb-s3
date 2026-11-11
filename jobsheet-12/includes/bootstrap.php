<?php
// Inisialisasi bersama: session, flash message, konstanta.
// Di-include oleh header.php dan auth.php (atau langsung oleh halaman proses).
require_once __DIR__ . '/helpers.php';   // memulai session dengan cookie aman
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/konstanta.php';

// Header keamanan dasar
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');

// Auto-login token "Ingat Saya". Cookie berisi token acak; database hanya menyimpan SHA-256-nya.
if (empty($_SESSION['user_id']) && !empty($_COOKIE['simpus_remember']) && preg_match('/^[a-f0-9]{64}$/', $_COOKIE['simpus_remember'])) {
    try {
        require_once __DIR__ . '/koneksi.php';
        $stmtRemember = $pdo->prepare(
            'SELECT u.id, u.nama, u.role FROM remember_tokens rt
             JOIN users u ON u.id = rt.user_id
             WHERE rt.token_hash = :hash AND rt.expires_at > NOW()'
        );
        $stmtRemember->execute([':hash' => hash('sha256', $_COOKIE['simpus_remember'])]);
        $rememberUser = $stmtRemember->fetch();
        if ($rememberUser) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $rememberUser['id'];
            $_SESSION['nama'] = $rememberUser['nama'];
            $_SESSION['role'] = $rememberUser['role'];
        } else {
            setcookie('simpus_remember', '', ['expires' => time() - 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
        }
    } catch (Throwable $e) {
        error_log('Remember-me lookup failed: ' . $e->getMessage());
    }
}

// CSP sebagai pertahanan tambahan terhadap XSS. Seluruh script/style lokal; inline style dibolehkan untuk kompatibilitas materi.
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'");
