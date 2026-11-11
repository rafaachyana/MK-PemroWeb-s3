<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}
csrf_verify();

$id = input_int($_POST, 'id') ?? 0;
$email = trim($_POST['email'] ?? '');
[$data, $errors] = validasi_anggota($_POST);
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Email tidak valid.'; }

if ($errors) {
    $_SESSION['old'] = $_POST;
    set_flash('error', $errors);
    header('Location: edit.php?id=' . $id);
    exit;
}

try {
    $stmt = $pdo->prepare(
        'UPDATE anggota
            SET nama = :nama, no_anggota = :no, alamat = :alamat, no_hp = :hp, email = :email
          WHERE id = :id'
    );
    $stmt->execute([':nama' => $data['nama'], ':no' => $data['no_anggota'], ':alamat' => $data['alamat'], ':hp' => $data['no_hp'], ':email' => $email, ':id' => $id]);
} catch (PDOException $e) {
    if ($e->getCode() === '23505') {
        set_flash('error', 'No. anggota sudah dipakai anggota lain.');
    } else {
        error_log('Gagal ubah anggota: ' . $e->getMessage());
        set_flash('error', 'Gagal mengubah anggota. Data tidak berubah.');
    }
    header('Location: edit.php?id=' . $id);
    exit;
}

if ($stmt->rowCount() === 0) {
    set_flash('error', 'Anggota tidak ditemukan.');
} else {
    set_flash('sukses', 'Data anggota berhasil diperbarui.');
}
header('Location: list.php');
exit;
