<?php
$host     = "localhost";
$port     = "5434"; // Menunjuk ke PostgreSQL 17 Port 5434
$user     = "postgres";
$pass     = "12345"; // Sesuaikan dengan password user postgres Anda
$database = "simpus_mini";

// Membuat DSN menggunakan variabel $database
$dsn = "pgsql:host=$host;port=$port;dbname=$database";

try {
    // Membuat instance PDO menggunakan variabel $pass
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Koneksi ke PostgreSQL (Port 5434) gagal: " . $e->getMessage());
}