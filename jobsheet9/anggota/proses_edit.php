<?php
session_start();
require_once "../config/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: list.php");
    exit;
}

$id            = (int)($_POST['id'] ?? 0);
$nama          = trim($_POST['nama'] ?? '');
$no_anggota    = trim($_POST['no_anggota'] ?? '');
$alamat        = trim($_POST['alamat'] ?? '');
$no_hp         = trim($_POST['no_hp'] ?? '');
$email         = trim($_POST['email'] ?? '');
$tgl_bergabung = trim($_POST['tgl_bergabung'] ?? '');

$errors = [];

// Validasi Sisi Server (sama seperti proses_tambah.php)
if ($id <= 0)           $errors[] = "ID anggota tidak valid.";
if (empty($nama))       $errors[] = "Nama anggota wajib diisi.";
if (empty($no_anggota)) $errors[] = "Nomor anggota wajib diisi.";

if (!empty($no_anggota) && !preg_match('/^[A-Za-z0-9-]+$/', $no_anggota)) {
    $errors[] = "No. Anggota hanya boleh huruf, angka, dan tanda hubung (-).";
}
if (!empty($no_hp) && !preg_match('/^[0-9+]+$/', $no_hp)) {
    $errors[] = "Nomor HP hanya boleh berisi angka dan tanda (+).";
}
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format alamat email tidak valid.";
}

if (!empty($errors)) {
    $_SESSION['flash_error'] = implode("<br>", $errors);
    header("Location: edit.php?id=" . $id);
    exit;
}

$tgl_val = !empty($tgl_bergabung) ? $tgl_bergabung : null;

$sql = "UPDATE anggota SET no_anggota = $1, nama = $2, alamat = $3, no_hp = $4, email = $5, tgl_bergabung = $6 WHERE id = $7";
$params = array($no_anggota, $nama, $alamat, $no_hp, $email, $tgl_val, $id);
$result = pg_query_params($koneksi, $sql, $params);

if ($result) {
    $_SESSION['flash_success'] = "Anggota <strong>\"" . htmlspecialchars($nama) . "\"</strong> berhasil diperbarui!";
    header("Location: list.php");
} else {
    $_SESSION['flash_error'] = "Gagal memperbarui data: " . pg_last_error($koneksi);
    header("Location: edit.php?id=" . $id);
}
exit;
