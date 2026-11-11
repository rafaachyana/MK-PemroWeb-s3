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
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('SELECT buku_id, status FROM peminjaman WHERE id = :id FOR UPDATE');
    $stmt->execute([':id' => $id]);
    $p = $stmt->fetch();

    if (!$p) {
        $pdo->rollBack();
        set_flash('error', 'Data peminjaman tidak ditemukan.');
    } elseif ($p['status'] !== 'dipinjam') {
        $pdo->rollBack();                 // cegah stok bertambah dua kali
        set_flash('error', 'Buku ini sudah dikembalikan sebelumnya.');
    } else {
        $stmt = $pdo->prepare("UPDATE peminjaman SET status = 'dikembalikan', tanggal_kembali = CURRENT_DATE WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $stmt = $pdo->prepare('UPDATE buku SET stok = stok + 1 WHERE id = :id');
        $stmt->execute([':id' => $p['buku_id']]);

        $pdo->commit();
        set_flash('sukses', 'Pengembalian berhasil dicatat dan stok buku bertambah.');
    }
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Gagal pengembalian: ' . $e->getMessage());
    set_flash('error', 'Pengembalian gagal diproses.');
}

header('Location: list.php?status=dipinjam');
exit;
