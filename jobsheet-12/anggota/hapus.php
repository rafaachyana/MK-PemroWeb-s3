<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';
wajib_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}
csrf_verify();

$id = input_int($_POST, 'id') ?? 0;

try {
    $stmt = $pdo->prepare('DELETE FROM anggota WHERE id = :id');
    $stmt->execute([':id' => $id]);
    if ($stmt->rowCount() === 0) {
        set_flash('error', 'Anggota tidak ditemukan.');
    } else {
        set_flash('sukses', 'Anggota berhasil dihapus.');
    }
} catch (PDOException $e) {
    if ($e->getCode() === '23503') {          // foreign_key_violation
        set_flash('error', 'Anggota tidak dapat dihapus karena masih tercatat dalam riwayat peminjaman.');
    } else {
        error_log('Gagal hapus anggota: ' . $e->getMessage());
        set_flash('error', 'Gagal menghapus data.');
    }
}
header('Location: list.php');
exit;
