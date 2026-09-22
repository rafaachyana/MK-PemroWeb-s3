<?php
$host     = "localhost";
$port     = "5434"; // Menunjuk ke PostgreSQL 17 Port 5434
$user     = "postgres";
$pass     = "12345"; // Sesuaikan dengan password user postgres Anda
$database = "simpus_mini";

$conn_string = "host={$host} port={$port} dbname={$database} user={$user} password={$pass}";

// Membuka koneksi ke PostgreSQL
$koneksi = pg_connect($conn_string);

if (!$koneksi) {
    die("Koneksi ke PostgreSQL (Port 5434) gagal: " . pg_last_error());
}
?>
