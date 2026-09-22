<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul     = trim($_POST['judul'] ?? '');
    $pengarang = trim($_POST['pengarang'] ?? '');
    $tahun     = trim($_POST['tahun'] ?? '');
    $isbn      = trim($_POST['isbn'] ?? '');
    $stok      = trim($_POST['stok'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');

    $errors = [];
    
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

    // 1.validasi ISBN menggunakan preg_match()
    if (!empty($isbn) && !preg_match('/^[0-9-]+$/', $isbn)) {
        $errors[] = "ISBN hanya boleh berisi angka dan tanda hubung (-).";
    }

    if (!empty($errors)) {
        $_SESSION['flash_error'] = implode("<br>", $errors);
        header("Location: tambah.php");
        exit;
    }

    if (!isset($_SESSION['buku'])) {
        $_SESSION['buku'] = [];
    }

    $_SESSION['buku'][] = [
        'judul'     => $judul,
        'pengarang' => $pengarang,
        'tahun'     => $tahun,
        'isbn'      => $isbn,
        'stok'      => $stok,
        'kategori'  => $kategori
    ];

    $_SESSION['flash_success'] = "Buku <strong>\"" . htmlspecialchars($judul) . "\"</strong> berhasil ditambahkan!";
    header("Location: list.php");
    exit;
} else {
    header("Location: tambah.php");
    exit;
}