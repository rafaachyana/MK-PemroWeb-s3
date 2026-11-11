<?php
$base = '../';
$judul_halaman = 'Tambah Anggota';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/header.php';

$v = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>
    <section>
      <h1>Tambah Anggota</h1>
      <form id="form-anggota" action="proses_tambah.php" method="post" novalidate>
        <?= csrf_field() ?>
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
