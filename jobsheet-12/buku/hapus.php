<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}
csrf_verify();

$id = input_int($_POST, 'id') ?? 0;

try {
    $stmt = $pdo->prepare('DELETE FROM buku WHERE id = :id');
    $stmt->execute([':id' => $id]);
    if ($stmt->rowCount() === 0) {
        set_flash('error', 'Buku tidak ditemukan.');
    } else {
        set_flash('sukses', 'Buku berhasil dihapus.');
    }
} catch (PDOException $e) {
    if ($e->getCode() === '23503') {          // foreign_key_violation
        set_flash('error', 'Buku tidak dapat dihapus karena masih tercatat dalam riwayat peminjaman.');
    } else {
        error_log('Gagal hapus buku: ' . $e->getMessage());
        set_flash('error', 'Gagal menghapus data.');
    }
}
header('Location: list.php');
exit;
