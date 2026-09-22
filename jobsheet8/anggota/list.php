<?php
session_start();
require_once "../config/koneksi.php";

$title = "SIMPUS-Mini | Daftar Anggota";
$base_path = "../";

// Query mengambil seluruh data anggota dari PostgreSQL
$query = "SELECT * FROM anggota ORDER BY id DESC";
$result = pg_query($koneksi, $query);

include_once "../includes/header.php";
?>

<section>
    <h2>Daftar Anggota</h2>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem;">
            <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <p id="table-counter" style="margin-bottom: 0.75rem; font-weight: 500; color: #55677a;"></p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No. Anggota</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No. HP</th>
                    <th>Email</th>
                    <th>Tanggal Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (pg_num_rows($result) === 0): ?>
                    <tr><td colspan="7" style="text-align:center;">Belum ada data anggota di PostgreSQL.</td></tr>
                <?php else: ?>
                    <?php while ($anggota = pg_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($anggota['no_anggota']) ?></td>
                            <td><?= htmlspecialchars($anggota['nama']) ?></td>
                            <td><?= htmlspecialchars($anggota['alamat'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($anggota['no_hp'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($anggota['email'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($anggota['tgl_bergabung'] ?? '-') ?></td>
                            <td>
                                <button type="button" class="btn-edit">Edit</button>
                                <button type="button" class="btn-delete">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include_once "../includes/footer.php"; ?>