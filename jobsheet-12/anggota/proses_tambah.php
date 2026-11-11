<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tambah.php');
    exit;
}
csrf_verify();

$email = trim($_POST['email'] ?? '');
[$data, $errors] = validasi_anggota($_POST);
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Email tidak valid.'; }

if ($errors) {
    $_SESSION['old'] = $_POST;
    set_flash('error', $errors);
    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO anggota (nama, no_anggota, alamat, no_hp, email)
         VALUES (:nama, :no, :alamat, :hp, :email)
         RETURNING id'
    );
    $stmt->execute([':nama' => $data['nama'], ':no' => $data['no_anggota'], ':alamat' => $data['alamat'], ':hp' => $data['no_hp'], ':email' => $data['email'] ?? $email]);
    $idBaru = $stmt->fetchColumn();
} catch (PDOException $e) {
    $_SESSION['old'] = $_POST;
    if ($e->getCode() === '23505') {            // unique_violation
        set_flash('error', 'No. anggota sudah dipakai anggota lain.');
    } else {
        error_log('Gagal tambah anggota: ' . $e->getMessage());
        set_flash('error', 'Gagal menyimpan anggota. Silakan coba lagi.');
    }
    header('Location: tambah.php');
    exit;
}

set_flash('sukses', 'Anggota "' . $data['nama'] . '" berhasil ditambahkan (ID ' . $idBaru . ').');
header('Location: list.php');
exit;
