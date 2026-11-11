<?php
$base = '../';
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}
csrf_verify();

$nama = input_str($_POST, 'nama', 100);
$username = input_str($_POST, 'username', 50);
$password = (string) ($_POST['password'] ?? '');
$konfirmasi = (string) ($_POST['konfirmasi'] ?? '');

$errors = [];
if ($nama === '') { $errors[] = 'Nama wajib diisi.'; }
if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
    $errors[] = 'Username 3-30 karakter: huruf, angka, atau garis bawah.';
}
if (strlen($password) < 8) { $errors[] = 'Password minimal 8 karakter.'; }
if ($password !== $konfirmasi) { $errors[] = 'Konfirmasi password tidak cocok.'; }

if ($errors) {
    $_SESSION['old'] = ['nama' => $nama, 'username' => $username];
    set_flash('error', $errors);
    header('Location: register.php');
    exit;
}

try {
    // Password disimpan sebagai hash (bcrypt), BUKAN plaintext. Role dipaksa 'petugas'.
    $stmt = $pdo->prepare('INSERT INTO users (nama, username, password, role) VALUES (:nama, :u, :p, :r)');
    $stmt->execute([
        ':nama' => $nama,
        ':u'    => $username,
        ':p'    => password_hash($password, PASSWORD_DEFAULT),
        ':r'    => 'petugas',
    ]);
} catch (PDOException $e) {
    $_SESSION['old'] = ['nama' => $nama, 'username' => $username];
    if ($e->getCode() === '23505') {
        set_flash('error', 'Username sudah dipakai.');
    } else {
        error_log('Gagal registrasi: ' . $e->getMessage());
        set_flash('error', 'Registrasi gagal. Silakan coba lagi.');
    }
    header('Location: register.php');
    exit;
}

set_flash('sukses', 'Registrasi berhasil. Silakan login.');
header('Location: login.php');
exit;
