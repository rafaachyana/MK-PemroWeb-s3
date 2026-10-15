<?php
session_start();
require_once "../config/koneksi.php";

$id = (int)($_GET['id'] ?? 0);
$res = pg_query_params($koneksi, "SELECT * FROM anggota WHERE id = $1", array($id));
$anggota = pg_fetch_assoc($res);

if (!$anggota) {
    $_SESSION['flash_error'] = "Data anggota tidak ditemukan.";
    header("Location: list.php");
    exit;
}

$title = "SIMPUS-Mini | Edit Anggota";
$base_path = "../";
include_once "../includes/header.php";
?>

<section>
    <h2>Edit Anggota</h2>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="flash flash-error"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="proses_edit.php">
        <input type="hidden" name="id" value="<?= (int)$anggota['id'] ?>">
        <p>
            <label for="nama">Nama Lengkap</label><br>
            <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($anggota['nama']) ?>" required>
        </p>
        <p>
            <label for="no_anggota">No. Anggota</label><br>
            <input type="text" id="no_anggota" name="no_anggota" value="<?= htmlspecialchars($anggota['no_anggota']) ?>" required>
        </p>
        <p>
            <label for="alamat">Alamat</label><br>
            <input type="text" id="alamat" name="alamat" value="<?= htmlspecialchars($anggota['alamat'] ?? '') ?>">
        </p>
        <p>
            <label for="no_hp">No. HP</label><br>
            <input type="text" id="no_hp" name="no_hp" value="<?= htmlspecialchars($anggota['no_hp'] ?? '') ?>">
        </p>
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($anggota['email'] ?? '') ?>">
        </p>
        <p>
            <label for="tgl_bergabung">Tanggal Bergabung</label><br>
            <input type="date" id="tgl_bergabung" name="tgl_bergabung" value="<?= htmlspecialchars($anggota['tgl_bergabung'] ?? '') ?>">
        </p>
        <p>
            <button type="submit">Update</button>
            <a href="list.php">Batal</a>
        </p>
    </form>
</section>

<?php include_once "../includes/footer.php"; ?>
