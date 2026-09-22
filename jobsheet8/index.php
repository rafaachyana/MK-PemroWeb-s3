<?php
session_start();
require_once "config/koneksi.php";

$title = "SIMPUS-Mini | Beranda";
$base_path = "./";

// Menghitung jumlah data langsung dari PostgreSQL
$q_buku      = pg_query($koneksi, "SELECT COUNT(*) AS total FROM buku");
$row_buku    = pg_fetch_assoc($q_buku);
$total_buku  = $row_buku['total'] ?? 0;

$q_anggota   = pg_query($koneksi, "SELECT COUNT(*) AS total FROM anggota");
$row_anggota = pg_fetch_assoc($q_anggota);
$total_anggota = $row_anggota['total'] ?? 0;

include_once "includes/header.php";
?>

<section>
    <h2>Selamat Datang di SIMPUS-Mini</h2>
    <p>Aplikasi pengelolaan perpustakaan terintegrasi dengan <strong>PostgreSQL 17 (Port 5434)</strong>.</p>
</section>

<section>
    <h2>Ringkasan Data (PostgreSQL)</h2>
    <article>
        <h3>Total Buku</h3>
        <p><?= $total_buku ?></p>
    </article>
    <article>
        <h3>Total Anggota</h3>
        <p><?= $total_anggota ?></p>
    </article>
    <article>
        <h3>Sedang Dipinjam</h3>
        <p>3</p>
    </article>
    <article>
        <h3>Buku Terlambat</h3>
        <p>1</p>
    </article>
</section>

<?php include_once "includes/footer.php"; ?>