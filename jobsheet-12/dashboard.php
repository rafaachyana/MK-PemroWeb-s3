<?php
$base = '';
$judul_halaman = 'Dashboard Petugas';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/koneksi.php';

$totalBuku    = (int) $pdo->query('SELECT COUNT(*) FROM buku')->fetchColumn();
$totalAnggota = (int) $pdo->query('SELECT COUNT(*) FROM anggota')->fetchColumn();
$dipinjam     = (int) $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'dipinjam'")->fetchColumn();
$terlambat    = (int) $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'dipinjam' AND tanggal_pinjam < CURRENT_DATE - 14")->fetchColumn();

require_once __DIR__ . '/includes/header.php';
?>
    <section>
      <h1>Dashboard Petugas</h1>
      <p>Halo, <strong><?= e($_SESSION['nama']) ?></strong> (<?= e($_SESSION['role']) ?>).</p>
      <?php if ($terlambat > 0): ?>
        <div class="alert alert-error"><ul><li><?= $terlambat ?> peminjaman terlambat (&gt; 14 hari belum kembali).</li></ul></div>
      <?php endif; ?>
      <div class="card-grid">
        <article class="card"><h3>Buku</h3><p class="card-angka"><?= $totalBuku ?></p></article>
        <article class="card"><h3>Anggota</h3><p class="card-angka"><?= $totalAnggota ?></p></article>
        <article class="card"><h3>Dipinjam</h3><p class="card-angka"><?= $dipinjam ?></p></article>
        <article class="card"><h3>Buku Terlambat</h3><p class="card-angka"><?= (int) $terlambat ?></p></article>
      </div>
      <h2>Pintasan</h2>
      <div class="shortcut">
        <a class="btn" href="peminjaman/tambah.php">Pinjamkan Buku</a>
        <a class="btn" href="peminjaman/list.php?status=dipinjam">Terima Pengembalian</a>
        <a class="btn btn-outline" href="peminjaman/riwayat.php">Riwayat per Anggota</a>
      </div>
    </section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
