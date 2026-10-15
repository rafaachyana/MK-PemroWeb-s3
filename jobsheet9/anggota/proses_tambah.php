<?php
session_start();
require_once "../config/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama          = trim($_POST['nama'] ?? '');
    $no_anggota    = trim($_POST['no_anggota'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');
    $no_hp         = trim($_POST['no_hp'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $tgl_bergabung = trim($_POST['tgl_bergabung'] ?? '');

    $errors = [];

    // Validasi Sisi Server
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
        header("Location: tambah.php");
        exit;
    }

    $tgl_val = !empty($tgl_bergabung) ? $tgl_bergabung : null;

    // Insert ke PostgreSQL
    $sql = "INSERT INTO anggota (no_anggota, nama, alamat, no_hp, email, tgl_bergabung) VALUES ($1, $2, $3, $4, $5, $6)";
    $params = array($no_anggota, $nama, $alamat, $no_hp, $email, $tgl_val);

    $result = pg_query_params($koneksi, $sql, $params);

    if ($result) {
        $_SESSION['flash_success'] = "Anggota <strong>\"" . htmlspecialchars($nama) . "\"</strong> berhasil disimpan ke PostgreSQL!";
        header("Location: list.php");
        exit;
    } else {
        $_SESSION['flash_error'] = "Gagal menyimpan data: " . pg_last_error($koneksi);
        header("Location: tambah.php");
        exit;
    }
} else {
    header("Location: tambah.php");
    exit;
}