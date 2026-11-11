<?php
$base = '../';
$judul_halaman = 'Daftar Anggota';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

// Pencarian server-side + pagination (LIMIT/OFFSET)
$perHalaman = 10;
$q = trim((string) ($_GET['q'] ?? ''));
$kw = '%' . $q . '%';

$stmt = $pdo->prepare('SELECT COUNT(*) FROM anggota WHERE nama ILIKE :kw');
$stmt->execute([':kw' => $kw]);
$total = (int) $stmt->fetchColumn();

$totalHalaman = max(1, (int) ceil($total / $perHalaman));
$halaman = min(max(1, (int) ($_GET['hal'] ?? 1)), $totalHalaman);
$offset = ($halaman - 1) * $perHalaman;

$stmt = $pdo->prepare('SELECT * FROM anggota WHERE nama ILIKE :kw ORDER BY id DESC LIMIT :lim OFFSET :off');
$stmt->bindValue(':kw', $kw);
$stmt->bindValue(':lim', $perHalaman, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$daftar = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>
<section>
      <div class="section-head">
        <h1>Daftar Anggota</h1>
        <a href="tambah.php" class="btn">+ Tambah Anggota</a>
      </div>
      <form method="get" class="form-cari">
        <label for="q">Cari nama:</label>
        <input type="search" id="q" name="q" value="<?= e($q) ?>" placeholder="Ketik nama...">
        <button type="submit" class="btn btn-sm">Cari</button>
        <?php if ($q !== ''): ?><a href="list.php" class="btn btn-sm btn-outline">Reset</a><?php endif; ?>
      </form>
      <div class="table-wrap">
        <table id="tabel-anggota">
          <thead>
            <tr><th>No. Anggota</th><th>Nama</th><th>Alamat</th><th>No. HP</th><th>Email</th><th>Tanggal Bergabung</th><th>Aksi</th></tr>
          </thead>
          <tbody>
          <?php if (!$daftar): ?>
            <tr><td colspan="7">Belum ada data.</td></tr>
          <?php endif; ?>
          <?php foreach ($daftar as $r): ?>
          <tr>
            <td><?= e($r['no_anggota']) ?></td>
            <td><?= e($r['nama']) ?></td>
            <td><?= e($r['alamat']) ?></td>
            <td><?= e($r['no_hp']) ?></td>
            <td><?= e($r['email'] ?? '-') ?></td>
            <td><?= e($r['created_at'] ?? '-') ?></td>
            <td class="aksi">
              <a href="edit.php?id=<?= (int) $r['id'] ?>" class="btn btn-sm">Edit</a>
              <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
              <form action="hapus.php" method="post" class="form-hapus inline-form">
                <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
              </form>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php if ($totalHalaman > 1): ?>
      <nav class="pagination" aria-label="Halaman data">
        <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
          <a href="?hal=<?= $i ?>&amp;q=<?= urlencode($q) ?>"<?= $i === $halaman ? ' class="aktif" aria-current="page"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
      </nav>
      <?php endif; ?>
      <p class="catatan">Total <?= (int) $total ?> data.</p>
    </section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
