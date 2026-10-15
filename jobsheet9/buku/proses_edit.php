<?php
session_start();
require_once "../config/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: list.php");
    exit;
}

$id        = (int)($_POST['id'] ?? 0);
$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun     = trim($_POST['tahun'] ?? '');
$isbn      = trim($_POST['isbn'] ?? '');
$stok      = trim($_POST['stok'] ?? '');
$kategori  = trim($_POST['kategori'] ?? '');

$errors = [];

// Validasi Sisi Server (sama seperti proses_tambah.php)
if ($id <= 0)          $errors[] = "ID buku tidak valid.";
if (empty($judul))     $errors[] = "Judul buku wajib diisi.";
if (empty($pengarang)) $errors[] = "Pengarang wajib diisi.";
if (empty($tahun))     $errors[] = "Tahun terbit wajib diisi.";
if ($stok === '')      $errors[] = "Stok wajib diisi.";

if (!empty($tahun) && ((int)$tahun < 1900 || (int)$tahun > 2026)) {
    $errors[] = "Tahun terbit harus di antara 1900-2026.";
}
if ($stok !== '' && (int)$stok < 0) {
    $errors[] = "Stok tidak boleh bernilai negatif.";
}
if (!empty($isbn) && !preg_match('/^[0-9-]+$/', $isbn)) {
    $errors[] = "ISBN hanya boleh berisi angka dan tanda hubung (-).";
}

if (!empty($errors)) {
    $_SESSION['flash_error'] = implode("<br>", $errors);
    header("Location: edit.php?id=" . $id);
    exit;
}

$sql = "UPDATE buku SET judul = $1, pengarang = $2, tahun = $3, isbn = $4, stok = $5, kategori = $6 WHERE id = $7";
$params = array($judul, $pengarang, (int)$tahun, $isbn, (int)$stok, $kategori, $id);
$result = pg_query_params($koneksi, $sql, $params);

if ($result) {
    $_SESSION['flash_success'] = "Buku <strong>\"" . htmlspecialchars($judul) . "\"</strong> berhasil diperbarui!";
    header("Location: list.php");
} else {
    $_SESSION['flash_error'] = "Gagal memperbarui data: " . pg_last_error($koneksi);
    header("Location: edit.php?id=" . $id);
}
exit;
