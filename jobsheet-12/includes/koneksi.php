<?php
// Koneksi PostgreSQL via PDO_PGSQL (Jobsheet 8).
$host = "localhost";
$port = "5434";
$db   = "simpus_mini";
$user = "postgres";
$pass = "12345";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
