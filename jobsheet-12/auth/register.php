<?php
$base = '../';
$judul_halaman = 'Daftar Petugas';
require_once __DIR__ . '/../includes/bootstrap.php';
$v = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
require_once __DIR__ . '/../includes/header.php';
?>
    <section class="auth-box">
      <h1>Daftar Petugas</h1>
      <form id="form-register" action="proses_register.php" method="post" novalidate>
        <?= csrf_field() ?>
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" value="<?= e($v['nama'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" value="<?= e($v['username'] ?? '') ?>" autocomplete="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password (min. 8 karakter)</label>
          <input type="password" id="password" name="password" autocomplete="new-password" required>
        </div>
        <div class="form-group">
          <label for="konfirmasi">Ulangi Password</label>
          <input type="password" id="konfirmasi" name="konfirmasi" autocomplete="new-password" required>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn">Daftar</button>
          <a href="login.php" class="btn btn-outline">Batal</a>
        </div>
      </form>
      <p class="catatan">Akun baru otomatis berperan <strong>petugas</strong>. Akun <strong>admin</strong> dibuat lewat skrip <code>sql/buat_admin.php</code>.</p>
    </section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
