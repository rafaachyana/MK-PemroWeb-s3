<?php
$base = '../';
$judul_halaman = 'Edit Anggota';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM anggota WHERE id = :id');
$stmt->execute([':id' => $id]);
$data = $stmt->fetch();

if (!$data) {
    set_flash('error', 'Anggota tidak ditemukan.');
    header('Location: list.php');
    exit;
}

$v = array_merge($data, $_SESSION['old'] ?? []);
unset($_SESSION['old']);

require_once __DIR__ . '/../includes/header.php';
?>
    <section>
      <h1>Edit Anggota</h1>
      <form class="form-konfirmasi-update" id="form-anggota" action="proses_edit.php" method="post" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $data['id'] ?>">
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" value="<?= e($v['nama'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label for="no_anggota">No. Anggota</label>
          <input type="text" id="no_anggota" name="no_anggota" placeholder="AG-006" value="<?= e($v['no_anggota'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <textarea id="alamat" name="alamat" rows="3"><?= e($v['alamat'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label for="no_hp">No. HP</label>
          <input type="tel" id="no_hp" name="no_hp" value="<?= e($v['no_hp'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= e($v['email'] ?? '') ?>" autocomplete="email">
        </div>
        <div class="form-actions">
          <button type="submit" class="btn">Simpan</button>
          <a href="list.php" class="btn btn-outline">Batal</a>
        </div>
      </form>
    </section>
