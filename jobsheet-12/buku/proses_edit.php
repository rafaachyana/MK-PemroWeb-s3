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
[$data, $errors] = validasi_buku($_POST);

if ($errors) {
    $_SESSION['old'] = $_POST;
    set_flash('error', $errors);
    header('Location: edit.php?id=' . $id);
    exit;
}

try {
    $stmt = $pdo->prepare(
        'UPDATE buku
            SET judul = :judul, pengarang = :pengarang, tahun = :tahun,
                isbn = :isbn, stok = :stok, kategori = :kategori
          WHERE id = :id'
    );
    $stmt->execute([':judul' => $data['judul'], ':pengarang' => $data['pengarang'], ':tahun' => $data['tahun'], ':isbn' => $data['isbn'] !== '' ? $data['isbn'] : null, ':stok' => $data['stok'], ':kategori' => $data['kategori'], ':id' => $id]);
} catch (PDOException $e) {
    error_log('Gagal ubah buku: ' . $e->getMessage());
    set_flash('error', 'Gagal mengubah buku. Data tidak berubah.');
    header('Location: edit.php?id=' . $id);
    exit;
}

if ($stmt->rowCount() === 0) {
    set_flash('error', 'Buku tidak ditemukan.');
} else {
    set_flash('sukses', 'Data buku berhasil diperbarui.');
}
header('Location: list.php');
exit;
