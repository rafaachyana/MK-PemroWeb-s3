<?php
session_start();
require_once "../config/koneksi.php";

$title = "SIMPUS-Mini | Daftar Anggota";
$base_path = "../";

// --- Pencarian & Pagination (server-side) ---
$per_page = 5;
$page     = max(1, (int)($_GET['page'] ?? 1));
$keyword  = trim($_GET['q'] ?? '');
$like     = '%' . $keyword . '%';

$res_total   = pg_query_params($koneksi, "SELECT COUNT(*) AS total FROM anggota WHERE nama ILIKE $1 OR no_anggota ILIKE $1", array($like));
$total_rows  = (int)pg_fetch_assoc($res_total)['total'];
$total_pages = max(1, (int)ceil($total_rows / $per_page));
$page        = min($page, $total_pages);
$offset      = ($page - 1) * $per_page;

$result = pg_query_params(
    $koneksi,
    "SELECT * FROM anggota WHERE nama ILIKE $1 OR no_anggota ILIKE $1 ORDER BY id DESC LIMIT $2 OFFSET $3",
    array($like, $per_page, $offset)
);

include_once "../includes/header.php";
?>

<section>
    <h2>Daftar Anggota</h2>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="flash flash-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="flash flash-error"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <form method="GET" action="list.php" class="search-box">
        <input type="text" id="search-input" name="q" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari nama / no. anggota...">
        <button type="submit">Cari</button>
        <?php if ($keyword !== ''): ?><a href="list.php">Reset</a><?php endif; ?>
    </form>

    <p id="table-counter">Menampilkan <?= pg_num_rows($result) ?> dari <?= $total_rows ?> anggota</p>

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
                    <tr><td colspan="7" style="text-align:center;">Tidak ada data anggota yang cocok.</td></tr>
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
                                <a href="edit.php?id=<?= (int)$anggota['id'] ?>" class="btn-edit">Edit</a>
                                <form class="form-hapus" method="POST" action="hapus.php">
                                    <input type="hidden" name="id" value="<?= (int)$anggota['id'] ?>">
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <nav class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="list.php?page=<?= $i ?><?= $keyword !== '' ? '&q=' . urlencode($keyword) : '' ?>"
               class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </nav>
</section>

<?php include_once "../includes/footer.php"; ?>
