<?php
$base = '';
$judul_halaman = 'Beranda';
require_once __DIR__ . '/includes/koneksi.php';
require_once __DIR__ . '/includes/header.php';

// Statistik real dari database (menggantikan angka dummy sejak Jobsheet 2)
$totalBuku    = (int) $pdo->query('SELECT COUNT(*) FROM buku')->fetchColumn();
$totalAnggota = (int) $pdo->query('SELECT COUNT(*) FROM anggota')->fetchColumn();
$dipinjam     = (int) $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'dipinjam'")->fetchColumn();
$sudahLogin   = !empty($_SESSION['user_id']);
?>
    
<section class="hero">
  <h1>Selamat Datang di SIMPUS-Mini</h1>
    <p>
      Sistem Informasi Perpustakaan Sederhana untuk mengelola buku, anggota, dan peminjaman.
    </p>
      <div class="hero-actions">
        <a href="katalog.php" class="btn btn-outline">
          Lihat Katalog
        </a>
      </div>
    </section>

    <section class="ringkasan-panel" aria-labelledby="judul-statistik">
      <h2 id="judul-statistik">Ringkasan Perpustakaan</h2>
      <div class="card-grid">
        <article class="card">
          <h3>Total Buku</h3>
          <p class="card-angka"><?= $totalBuku ?></p>
        </article>
        <article class="card">
          <h3>Total Anggota</h3>
          <p class="card-angka"><?= $totalAnggota ?></p>
        </article>
        <article class="card">
          <h3>Sedang Dipinjam</h3>
          <p class="card-angka"><?= $dipinjam ?></p>
        </article>
      </div>
    </section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
