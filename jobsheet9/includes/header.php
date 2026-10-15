<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$page_title = isset($title) ? $title : "SIMPUS-Mini";
$base_url = isset($base_path) ? $base_path : "./";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
</head>
<body>
    <header>
        <h1>SIMPUS-Mini</h1>
        <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
        <nav>
            <ul>
                <li><a href="<?= $base_url ?>index.php">Beranda</a></li>
                <li><a href="<?= $base_url ?>buku/list.php">Daftar Buku</a></li>
                <li><a href="<?= $base_url ?>buku/tambah.php">Tambah Buku</a></li>
                <li><a href="<?= $base_url ?>anggota/list.php">Daftar Anggota</a></li>
                <li><a href="<?= $base_url ?>anggota/tambah.php">Tambah Anggota</a></li>
            </ul>
        </nav>
    </header>
    <main>