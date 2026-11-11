<?php
// Header bersama (Jobsheet 7). Halaman pemanggil wajib mengatur:
//   $base           -> '' untuk halaman root, '../' untuk halaman di subfolder
//   $judul_halaman  -> judul pada <title>
require_once __DIR__ . '/bootstrap.php';
$base = $base ?? '';
$judul_halaman = $judul_halaman ?? 'SIMPUS-Mini';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($judul_halaman) ?> - SIMPUS-Mini</title>
  <link rel="stylesheet" href="<?= $base ?>assets/css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a href="<?= $base ?>index.php" class="logo">SIMPUS-Mini</a>
      <button type="button" class="menu-btn" id="menu-btn" aria-label="Buka menu" aria-expanded="false">&#9776;</button>
      <nav id="main-nav">
        <ul>
            <li><a href="<?= $base ?>index.php">Beranda</a></li>
            <li><a href="<?= $base ?>katalog.php">Katalog</a></li>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <li><a href="<?= $base ?>dashboard.php">Dashboard</a></li>
                <li><a href="<?= $base ?>buku/list.php">Buku</a></li>
                <li><a href="<?= $base ?>anggota/list.php">Anggota</a></li>
                <li><a href="<?= $base ?>peminjaman/list.php">Peminjaman</a></li>
                <li><a href="<?= $base ?>auth/logout.php">Logout (<?= e($_SESSION['nama'] ?? '') ?>)</a></li>
            <?php else: ?>
                <li><a href="<?= $base ?>auth/login.php">Login</a></li>
                <li><a href="<?= $base ?>auth/register.php">Daftar</a></li>
            <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container">
    <?php tampil_flash(); ?>
