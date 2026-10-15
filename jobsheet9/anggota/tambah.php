<?php
session_start();
$title = "SIMPUS-Mini | Tambah Anggota";
$base_path = "../";

include_once "../includes/header.php";
?>

<section>
    <h2>Tambah Anggota</h2>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem;">
            <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="proses_tambah.php">
        <p>
            <label for="nama">Nama Lengkap</label><br>
            <input type="text" id="nama" name="nama" required>
        </p>
        <p>
            <label for="no_anggota">No. Anggota</label><br>
            <input type="text" id="no_anggota" name="no_anggota" required>
        </p>
        <p>
            <label for="alamat">Alamat</label><br>
            <input type="text" id="alamat" name="alamat">
        </p>
        <p>
            <label for="no_hp">No. HP</label><br>
            <input type="text" id="no_hp" name="no_hp">
        </p>
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email">
        </p>
        <p>
            <label for="tgl_bergabung">Tanggal Bergabung</label><br>
            <input type="date" id="tgl_bergabung" name="tgl_bergabung">
        </p>
        <p>
            <button type="submit">Simpan</button>
        </p>
    </form>
</section>

<?php include_once "../includes/footer.php"; ?>