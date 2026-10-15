<?php
session_start();
require_once "../config/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul     = trim($_POST['judul'] ?? '');
    $pengarang = trim($_POST['pengarang'] ?? '');
    $tahun     = trim($_POST['tahun'] ?? '');
    $isbn      = trim($_POST['isbn'] ?? '');
    $stok      = trim($_POST['stok'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');

    $errors = [];

    // Validasi Sisi Server
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
        header("Location: tambah.php");
        exit;
    }

    // Insert ke PostgreSQL Menggunakan Parameterized Query ($1, $2, dst.)
    $sql = "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori) VALUES ($1, $2, $3, $4, $5, $6)";
    $params = array($judul, $pengarang, (int)$tahun, $isbn, (int)$stok, $kategori);

    $result = pg_query_params($koneksi, $sql, $params);

    if ($result) {
        $_SESSION['flash_success'] = "Buku <strong>\"" . htmlspecialchars($judul) . "\"</strong> berhasil disimpan ke PostgreSQL!";
        header("Location: list.php");
        exit;
    } else {
        $_SESSION['flash_error'] = "Gagal menyimpan data ke PostgreSQL: " . pg_last_error($koneksi);
        header("Location: tambah.php");
        exit;
    }
} else {
    header("Location: tambah.php");
    exit;
}