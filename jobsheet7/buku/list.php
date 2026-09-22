<?php
session_start();
$title = "SIMPUS-Mini | Daftar Buku";
$base_path = "../";

$daftar_buku = $_SESSION['buku'] ?? [];

include_once "../includes/header.php";
?>

<section>
    <h2>Daftar Buku</h2>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem;">
            <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <div class="search-box" style="display: flex; gap: 10px; margin-bottom: 1rem;">
        <input type="text" id="search-input" placeholder="Cari data buku..." style="flex: 1; max-width: 300px;">
    </div>

    <p id="table-counter" style="font-weight: bold; margin-bottom: 0.5rem; color: #555;"></p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <th>ISBN</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_buku)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Belum ada data buku.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftar_buku as $buku): ?>
                        <tr>
                            <td><?= htmlspecialchars($buku['judul']) ?></td>
                            <td><?= htmlspecialchars($buku['pengarang']) ?></td>
                            <td><?= htmlspecialchars($buku['tahun']) ?></td>
                            <td><?= htmlspecialchars($buku['isbn'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($buku['stok']) ?></td>
                            <td><?= htmlspecialchars($buku['kategori']) ?></td>
                            <td>
                                <button type="button" class="btn-edit">Edit</button>
                                <button type="button" class="btn-delete">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include_once "../includes/footer.php"; ?>