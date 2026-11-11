<?php
$base = '../';
$judul_halaman = 'Login';
require_once __DIR__ . '/../includes/bootstrap.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit;
}
if (isset($_GET['keluar'])) {
    set_flash('sukses', 'Anda sudah logout.');
}
require_once __DIR__ . '/../includes/header.php';
?>
    <section class="auth-box">
      <h1>Login Petugas</h1>
      <form id="form-login" action="proses_login.php" method="post" novalidate>
        <?= csrf_field() ?>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" autocomplete="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" autocomplete="current-password" required>
        </div>
        <div class="form-group">
          <label><input type="checkbox" name="remember_me" value="1"> Ingat Saya (30 hari di perangkat pribadi)</label>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn">Masuk</button>
        </div>
      </form>
      <p class="catatan">Belum punya akun? <a href="register.php">Daftar sebagai petugas</a>.</p>
    </section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
