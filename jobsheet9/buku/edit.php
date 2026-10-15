<?php
session_start();
require_once "../config/koneksi.php";

$id = (int)($_GET['id'] ?? 0);
$res = pg_query_params($koneksi, "SELECT * FROM buku WHERE id = $1", array($id));
$buku = pg_fetch_assoc($res);

if (!$buku) {
    $_SESSION['flash_error'] = "Data buku tidak ditemukan.";
    header("Location: list.php");
    exit;
}

$title = "SIMPUS-Mini | Edit Buku";
$base_path = "../";
include_once "../includes/header.php";
?>

<section>
    <h2>Edit Buku</h2>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="flash flash-error"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="proses_edit.php">
        <input type="hidden" name="id" value="<?= (int)$buku['id'] ?>">
        <p>
            <label for="judul">Judul Buku</label><br>
            <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($buku['judul']) ?>" required>
        </p>
        <p>
            <label for="pengarang">Pengarang</label><br>
            <input type="text" id="pengarang" name="pengarang" value="<?= htmlspecialchars($buku['pengarang']) ?>" required>
        </p>
        <p>
            <label for="tahun">Tahun Terbit</label><br>
            <input type="number" id="tahun" name="tahun" min="1900" max="2026" value="<?= htmlspecialchars($buku['tahun']) ?>" required>
        </p>
        <p>
            <label for="isbn">ISBN</label><br>
            <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($buku['isbn'] ?? '') ?>">
        </p>
        <p>
            <label for="stok">Stok</label><br>
            <input type="number" id="stok" name="stok" min="0" value="<?= htmlspecialchars($buku['stok']) ?>" required>
        </p>
        <p>
            <label for="kategori">Kategori</label><br>
            <select id="kategori" name="kategori">
                <?php foreach (['Fiksi', 'Non-Fiksi', 'Referensi'] as $kat): ?>
                    <option value="<?= $kat ?>" <?= $buku['kategori'] === $kat ? 'selected' : '' ?>><?= $kat ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <button type="submit">Update</button>
            <a href="list.php">Batal</a>
        </p>
    </form>
</section>

<?php include_once "../includes/footer.php"; ?>
