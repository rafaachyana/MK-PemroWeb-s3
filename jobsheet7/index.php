<?php
session_start();
$title = "SIMPUS-Mini | Beranda";
$base_path = "./";

// Data Awal Default jika Session Masih Kosong
if (!isset($_SESSION['buku'])) {
    $_SESSION['buku'] = [
        ["judul" => "Laskar Pelangi", "pengarang" => "Andrea Hirata", "tahun" => 2005, "stok" => 4, "kategori" => "Fiksi", "isbn" => "978-979-3062-79-2"],
        ["judul" => "Bumi Manusia", "pengarang" => "Pramoedya Ananta Toer", "tahun" => 1980, "stok" => 2, "kategori" => "Fiksi", "isbn" => "978-979-97312-3-5"],
        ["judul" => "Filosofi Teras", "pengarang" => "Henry Manampiring", "tahun" => 2018, "stok" => 5, "kategori" => "Non-Fiksi", "isbn" => "978-602-424-694-5"]
    ];
}

if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [
        ["no_anggota" => "A001", "nama" => "Siti Aminah", "alamat" => "Malang", "no_hp" => "08123456789", "email" => "sitiaminah01@gmail.com", "tgl_bergabung" => "2018-01-22"],
        ["no_anggota" => "A002", "nama" => "Budi Santoso", "alamat" => "Batu", "no_hp" => "08139876543", "email" => "budisantosoganteng@gmail.com", "tgl_bergabung" => "2020-06-15"]
    ];
}

$total_buku = count($_SESSION['buku']);
$total_anggota = count($_SESSION['anggota']);

include_once "includes/header.php";
?>

<section>
    <h2>Selamat Datang di Sistem Perpustakaan Mini</h2>
    <p>Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan berbasis PHP Session.</p>
</section>

<section>
    <h2>Ringkasan</h2>
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
        <h3>Buku Terlambat Dikembalikan</h3>
        <p>2</p>
    </article>
</section>

<?php include_once "includes/footer.php"; ?>