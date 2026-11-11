<?php
// Katalog buku untuk Tamu: hanya-baca, tanpa login (aktor "Tamu" pada Jobsheet 4).
$base = '';
$judul_halaman = 'Katalog Buku';
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/koneksi.php';

$q = trim((string) ($_GET['q'] ?? ''));
$perHalaman = 10;
$stmt = $pdo->prepare(
    'SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw'
);
$stmt->execute([':kw' => '%' . $q . '%']);
$totalBuku = (int) $stmt->fetchColumn();

$totalHalaman = max(1, (int) ceil($totalBuku / $perHalaman));
$halamanInput = filter_input(INPUT_GET, 'hal', FILTER_VALIDATE_INT);
$halaman = ($halamanInput !== false && $halamanInput !== null && $halamanInput > 0)
    ? min($halamanInput, $totalHalaman)
    : 1;
$offset = ($halaman - 1) * $perHalaman;

$stmt = $pdo->prepare(
    'SELECT judul, pengarang, tahun, kategori, stok
     FROM buku
     WHERE judul ILIKE :kw
     ORDER BY judul
     LIMIT :lim OFFSET :off'
);
$stmt->bindValue(':kw', '%' . $q . '%', PDO::PARAM_STR);
$stmt->bindValue(':lim', $perHalaman, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$daftar = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
    <section>
      <div class="section-head"><h1>Katalog Buku</h1></div>
      <form method="get" class="form-cari">
        <label for="q">Cari judul:</label>
        <input
          type="search"
          id="q"
          name="q"
          value="<?= e($q) ?>"
          placeholder="Ketik judul..."
        >
        <button type="submit" class="btn btn-sm">Cari</button>
      </form>

      <div class="table-wrap">
        <table>
          <thead><tr><th>Judul</th><th>Pengarang</th><th>Tahun</th><th>Kategori</th><th>Ketersediaan</th></tr></thead>
          <tbody>
          <?php if (!$daftar): ?><tr><td colspan="5">Buku tidak ditemukan.</td></tr><?php endif; ?>
          <?php foreach ($daftar as $r): ?>
          <tr>
            <td><?= e($r['judul']) ?></td>
            <td><?= e($r['pengarang']) ?></td>
            <td><?= e($r['tahun']) ?></td>
            <td><?= e($r['kategori']) ?></td>
            <td><?= (int) $r['stok'] > 0 ? 'Tersedia (' . (int) $r['stok'] . ')' : 'Habis' ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <?php if ($totalHalaman > 1): ?>
        <nav class="pagination" aria-label="Navigasi halaman katalog">
          <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
            <a
              href="?hal=<?= $i ?>&amp;q=<?= urlencode($q) ?>"
              <?= $i === $halaman
                  ? 'class="aktif" aria-current="page"'
                  : '' ?>
            ><?= $i ?></a>
          <?php endfor; ?>
        </nav>
      <?php endif; ?>

      <p class="catatan">
        Menampilkan <?= count($daftar) ?> dari <?= $totalBuku ?> buku.
        Halaman <?= $halaman ?> dari <?= $totalHalaman ?>.
      </p>
    </section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
