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

[$data, $errors] = validasi_buku($_POST);

if ($errors) {
    $_SESSION['old'] = $_POST;
    set_flash('error', $errors);
    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
         VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)
         RETURNING id'
    );
    $stmt->execute([':judul' => $data['judul'], ':pengarang' => $data['pengarang'], ':tahun' => $data['tahun'], ':isbn' => $data['isbn'] !== '' ? $data['isbn'] : null, ':stok' => $data['stok'], ':kategori' => $data['kategori']]);
    $idBaru = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log('Gagal tambah buku: ' . $e->getMessage());
    $_SESSION['old'] = $_POST;
    set_flash('error', 'Gagal menyimpan buku. Silakan coba lagi.');
    header('Location: tambah.php');
    exit;
}

set_flash('sukses', 'Buku "' . $data['judul'] . '" berhasil ditambahkan (ID ' . $idBaru . ').');
header('Location: list.php');
exit;
