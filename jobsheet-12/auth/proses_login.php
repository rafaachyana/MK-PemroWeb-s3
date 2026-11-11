<?php
$base = '../';
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}
csrf_verify();

$username = input_str($_POST, 'username', 50);
$password = (string) ($_POST['password'] ?? '');

// Pembatasan percobaan per username dalam sesi ini; produksi sebaiknya juga memakai cache/IP.
if (!isset($_SESSION['login_gagal'])) $_SESSION['login_gagal'] = [];
$kunciLogin = strtolower($username);
$percobaan = $_SESSION['login_gagal'][$kunciLogin] ?? ['jumlah' => 0, 'sampai' => 0];
if (($percobaan['sampai'] ?? 0) > time()) {
    set_flash('error', 'Terlalu banyak percobaan gagal. Coba lagi beberapa menit.');
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, nama, username, password, role FROM users WHERE username = :u');
$stmt->execute([':u' => $username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);   // cegah session fixation
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['nama']    = $user['nama'];
    $_SESSION['role']    = $user['role'];

    unset($_SESSION['login_gagal'][$kunciLogin]);
    if (!empty($_POST['remember_me'])) {
        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $stmtToken = $pdo->prepare("INSERT INTO remember_tokens (user_id, token_hash, expires_at) VALUES (:uid, :hash, NOW() + INTERVAL '30 days')");
        $stmtToken->execute([':uid' => (int) $user['id'], ':hash' => $hash]);
        setcookie('simpus_remember', $token, [
            'expires' => time() + 60 * 60 * 24 * 30,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
    set_flash('sukses', 'Selamat datang, ' . $user['nama'] . '.');
    header('Location: ../dashboard.php');
    exit;
}

// Pesan generik: tidak membocorkan apakah username atau password yang salah
$percobaan['jumlah'] = ($percobaan['jumlah'] ?? 0) + 1;
if ($percobaan['jumlah'] >= 5) {
    $percobaan['sampai'] = time() + 300;
    $percobaan['jumlah'] = 0;
}
$_SESSION['login_gagal'][$kunciLogin] = $percobaan;
set_flash('error', 'Username atau password salah.');
header('Location: login.php');
exit;
