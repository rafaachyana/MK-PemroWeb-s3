<?php
$base = '../';
$judul_halaman = 'Form Peminjaman';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';

// Dropdown: semua anggota & hanya buku yang stoknya > 0
$anggota = $pdo->query('SELECT id, nama, no_anggota FROM anggota ORDER BY nama')->fetchAll();
$buku    = $pdo->query('SELECT id, judul, stok FROM buku WHERE stok > 0 ORDER BY judul')->fetchAll();
$v = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

require_once __DIR__ . '/../includes/header.php';
?>
    <section>
      <h1>Form Peminjaman</h1>
      <?php if (!$buku): ?>
        <div class="alert alert-error"><ul><li>Tidak ada buku dengan stok tersedia.</li></ul></div>
      <?php endif; ?>
      <form id="form-pinjam" action="proses_tambah.php" method="post" novalidate>
        <?= csrf_field() ?>
        <div class="form-group">
          <label for="anggota_id">Anggota</label>
          <select id="anggota_id" name="anggota_id" required>
            <option value="">-- Pilih anggota --</option>
            <?php foreach ($anggota as $a): ?>
              <option value="<?= (int) $a['id'] ?>"<?= (int) ($v['anggota_id'] ?? 0) === (int) $a['id'] ? ' selected' : '' ?>><?= e($a['nama']) ?> (<?= e($a['no_anggota']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="buku_id">Buku (hanya stok &gt; 0)</label>
          <select id="buku_id" name="buku_id" required>
            <option value="">-- Pilih buku --</option>
            <?php foreach ($buku as $b): ?>
              <option value="<?= (int) $b['id'] ?>"<?= (int) ($v['buku_id'] ?? 0) === (int) $b['id'] ? ' selected' : '' ?>><?= e($b['judul']) ?> (stok <?= (int) $b['stok'] ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn">Simpan Peminjaman</button>
          <a href="list.php" class="btn btn-outline">Batal</a>
        </div>
      </form>
    </section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
