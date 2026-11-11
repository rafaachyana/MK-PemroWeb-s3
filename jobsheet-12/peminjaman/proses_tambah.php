<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tambah.php');
    exit;
}
csrf_verify();

$anggotaId = input_int($_POST, 'anggota_id');
$bukuId    = input_int($_POST, 'buku_id');

function tolak(string $pesan): void
{
    set_flash('error', $pesan);
    $_SESSION['old'] = ['anggota_id' => $_POST['anggota_id'] ?? '', 'buku_id' => $_POST['buku_id'] ?? ''];
    header('Location: tambah.php');
    exit;
}

if ($anggotaId === null || $bukuId === null) {
    tolak('Anggota dan buku wajib dipilih.');
}

try {
    // Satu transaksi: cek aturan -> simpan peminjaman -> kurangi stok. Gagal di mana pun = batal semua.
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('SELECT id, nama FROM anggota WHERE id = :id');
    $stmt->execute([':id' => $anggotaId]);
    $anggota = $stmt->fetch();
    if (!$anggota) {
        $pdo->rollBack();
        tolak('Anggota tidak ditemukan.');
    }

    // Baris buku dikunci (FOR UPDATE) agar dua petugas tidak bisa memakai stok terakhir bersamaan
    $stmt = $pdo->prepare('SELECT judul, stok FROM buku WHERE id = :id FOR UPDATE');
    $stmt->execute([':id' => $bukuId]);
    $buku = $stmt->fetch();
    if (!$buku) {
        $pdo->rollBack();
        tolak('Buku tidak ditemukan.');
    }
    if ((int) $buku['stok'] <= 0) {
        $pdo->rollBack();
        tolak('Stok buku "' . $buku['judul'] . '" sedang habis.');
    }

    // Cegah anggota meminjam buku yang sama lagi sebelum pinjaman pertama dikembalikan.
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM peminjaman WHERE anggota_id = :a AND buku_id = :b AND status = 'dipinjam'");
    $stmt->execute([':a' => $anggotaId, ':b' => $bukuId]);
    if ((int) $stmt->fetchColumn() > 0) {
        $pdo->rollBack();
        tolak('Anggota ini sudah memiliki pinjaman aktif untuk buku yang sama.');
    }

    // Validasi bisnis: anggota dengan pinjaman > 14 hari yang belum kembali tidak boleh meminjam lagi
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM peminjaman
          WHERE anggota_id = :a AND status = 'dipinjam' AND tanggal_pinjam < CURRENT_DATE - 14"
    );
    $stmt->execute([':a' => $anggotaId]);
    if ((int) $stmt->fetchColumn() > 0) {
        $pdo->rollBack();
        tolak($anggota['nama'] . ' masih memiliki pinjaman terlambat (> 14 hari). Selesaikan pengembalian dahulu.');
    }

    $stmt = $pdo->prepare('INSERT INTO peminjaman (buku_id, anggota_id, petugas_id) VALUES (:b, :a, :p)');
    $stmt->execute([':b' => $bukuId, ':a' => $anggotaId, ':p' => $_SESSION['user_id']]);

    $stmt = $pdo->prepare('UPDATE buku SET stok = stok - 1 WHERE id = :id AND stok > 0');
    $stmt->execute([':id' => $bukuId]);
    if ($stmt->rowCount() !== 1) {
        throw new RuntimeException('Stok gagal dikurangi.');
    }

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Gagal peminjaman: ' . $e->getMessage());
    tolak('Peminjaman gagal diproses. Silakan coba lagi.');
}

set_flash('sukses', '"' . $buku['judul'] . '" berhasil dipinjamkan kepada ' . $anggota['nama'] . '.');
header('Location: list.php?status=dipinjam');
exit;
