<?php
require_once "includes/auth.php";
require_once "config/koneksi.php";

$title = "SIMPUS-Mini | Dashboard";
$base_path = "./";

$total_buku    = (int) pg_fetch_assoc(pg_query($koneksi, "SELECT COUNT(*) AS total FROM buku"))['total'];
$total_anggota = (int) pg_fetch_assoc(pg_query($koneksi, "SELECT COUNT(*) AS total FROM anggota"))['total'];
$total_stok    = (int) pg_fetch_assoc(pg_query($koneksi, "SELECT COALESCE(SUM(stok), 0) AS total FROM buku"))['total'];
$total_petugas = (int) pg_fetch_assoc(pg_query($koneksi, "SELECT COUNT(*) AS total FROM users"))['total'];

include_once "includes/header.php";
?>

<section>
    <h2>Dashboard Petugas</h2>
    <?php tampil_flash(); ?>
    <p>Halo, <strong><?= htmlspecialchars($_SESSION['nama'] ?? '') ?></strong> <span class="badge-role"><?= htmlspecialchars($_SESSION['role'] ?? '') ?></span></p>
</section>

<section>
    <article>
        <h3>Total Buku</h3>
        <p><?= $total_buku ?></p>
    </article>
    <article>
        <h3>Total Anggota</h3>
        <p><?= $total_anggota ?></p>
    </article>
    <article>
        <h3>Total Stok</h3>
        <p><?= $total_stok ?></p>
    </article>
    <article>
        <h3>Petugas Terdaftar</h3>
        <p><?= $total_petugas ?></p>
    </article>
</section>

<section>
    <h2>Pintasan</h2>
    <div class="shortcut">
        <a href="buku/tambah.php">+ Tambah Buku</a>
        <a href="anggota/tambah.php">+ Tambah Anggota</a>
        <a href="buku/list.php" class="sekunder">Daftar Buku</a>
        <a href="anggota/list.php" class="sekunder">Daftar Anggota</a>
    </div>
    <p class="catatan">Fitur peminjaman &amp; pengembalian dibuat pada Jobsheet 12.</p>
</section>

<?php include_once "includes/footer.php"; ?>