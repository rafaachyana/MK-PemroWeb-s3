<?php
$base = '../';
$judul_halaman = 'Edit Buku';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM buku WHERE id = :id');
$stmt->execute([':id' => $id]);
$data = $stmt->fetch();

if (!$data) {
    set_flash('error', 'Buku tidak ditemukan.');
    header('Location: list.php');
    exit;
}

$v = array_merge($data, $_SESSION['old'] ?? []);   // isian lama bila validasi gagal, selain itu data DB
unset($_SESSION['old']);

require_once __DIR__ . '/../includes/header.php';
?>
    <section>
      <h1>Edit Buku</h1>
      <form class="form-konfirmasi-update" id="form-buku" action="proses_edit.php" method="post" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $data['id'] ?>">
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
