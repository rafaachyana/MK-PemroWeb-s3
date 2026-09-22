<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama          = trim($_POST['nama'] ?? '');
    $no_anggota    = trim($_POST['no_anggota'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');
    $no_hp         = trim($_POST['no_hp'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $tgl_bergabung = trim($_POST['tgl_bergabung'] ?? '');

    $errors = [];

    //2. validasi lengkap form anggota
    if (empty($nama))       $errors[] = "Nama anggota wajib diisi.";
    if (empty($no_anggota)) $errors[] = "Nomor anggota wajib diisi.";

    if (!empty($no_anggota) && !preg_match('/^[A-Za-z0-9-]+$/', $no_anggota)) {
        $errors[] = "No. Anggota hanya boleh berisi huruf, angka, dan tanda hubung (-).";
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

    if (!isset($_SESSION['anggota'])) {
        $_SESSION['anggota'] = [];
    }

    $_SESSION['anggota'][] = [
        'no_anggota'    => $no_anggota,
        'nama'          => $nama,
        'alamat'        => $alamat,
        'no_hp'         => $no_hp,
        'email'         => $email,
        'tgl_bergabung' => $tgl_bergabung
    ];

    $_SESSION['flash_success'] = "Anggota <strong>\"" . htmlspecialchars($nama) . "\"</strong> berhasil ditambahkan!";
    header("Location: list.php");
    exit;
} else {
    header("Location: tambah.php");
    exit;
}