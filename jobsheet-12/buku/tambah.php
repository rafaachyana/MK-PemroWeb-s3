<?php
$base = '../';
$judul_halaman = 'Tambah Buku';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/header.php';

$v = $_SESSION['old'] ?? [];   // isian sebelumnya bila validasi gagal
unset($_SESSION['old']);
?>
    <section>
      <h1>Tambah Buku</h1>
      <form id="form-buku" action="proses_tambah.php" method="post" novalidate>
        <?= csrf_field() ?>
        <div class="form-group">
          <label for="judul">Judul</label>
          <input type="text" id="judul" name="judul" value="<?= e($v['judul'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label for="pengarang">Pengarang</label>
          <input type="text" id="pengarang" name="pengarang" value="<?= e($v['pengarang'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label for="tahun">Tahun Terbit</label>
          <input type="number" id="tahun" name="tahun" min="1000" max="2100" value="<?= e($v['tahun'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label for="isbn">ISBN</label>
          <input type="text" id="isbn" name="isbn" value="<?= e($v['isbn'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="stok">Stok</label>
          <input type="number" id="stok" name="stok" min="0" value="<?= e($v['stok'] ?? 1) ?>" required>
        </div>
        <div class="form-group">
          <label for="kategori">Kategori</label>
          <select id="kategori" name="kategori" required>
            <option value="">-- Pilih kategori --</option>
            <?php foreach (KATEGORI_BUKU as $k): ?>
              <option value="<?= e($k) ?>"<?= ($v['kategori'] ?? '') === $k ? ' selected' : '' ?>><?= e($k) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn">Simpan</button>
          <a href="list.php" class="btn btn-outline">Batal</a>
        </div>
      </form>
    </section>
