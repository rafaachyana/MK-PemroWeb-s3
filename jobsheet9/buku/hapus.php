<?php
session_start();
require_once "../config/koneksi.php";

// Hanya menerima POST agar penghapusan tidak terpicu lewat link/crawler
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: list.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id > 0) {
    $result = pg_query_params($koneksi, "DELETE FROM buku WHERE id = $1", array($id));
    if ($result && pg_affected_rows($result) > 0) {
        $_SESSION['flash_success'] = "Buku berhasil dihapus.";
    } else {
        $_SESSION['flash_error'] = "Buku gagal dihapus atau tidak ditemukan.";
    }
} else {
    $_SESSION['flash_error'] = "ID buku tidak valid.";
}

header("Location: list.php");
exit;
