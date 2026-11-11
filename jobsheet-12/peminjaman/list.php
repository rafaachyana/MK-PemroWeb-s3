<?php
$base = '../';
$judul_halaman = 'Daftar Peminjaman';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';

$status = ($_GET['status'] ?? '') === 'dipinjam' ? 'dipinjam' : 'semua';

$sql = 'SELECT p.id, p.tanggal_pinjam, p.tanggal_jatuh_tempo, p.tanggal_kembali, p.status,
               (p.status = \'dipinjam\' AND p.tanggal_pinjam < CURRENT_DATE - 14) AS terlambat,
               b.judul, a.nama AS nama_anggota, a.no_hp
          FROM peminjaman p
          JOIN buku b    ON b.id = p.buku_id
          JOIN anggota a ON a.id = p.anggota_id';
if ($status === 'dipinjam') {
    $sql .= " WHERE p.status = 'dipinjam'";
}
$sql .= ' ORDER BY p.id DESC LIMIT 200';
$daftar = $pdo->query($sql)->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>
    <section>
      <div class="section-head">
        <h1>Peminjaman</h1>
        <a href="tambah.php" class="btn">+ Pinjamkan Buku</a>
      </div>
      <p class="tab-status">
        <a href="list.php?status=dipinjam"<?= $status === 'dipinjam' ? ' class="aktif"' : '' ?>>Sedang dipinjam (pengembalian)</a>
        <a href="list.php"<?= $status === 'semua' ? ' class="aktif"' : '' ?>>Semua</a>
        <a href="riwayat.php">Riwayat per anggota</a>
      </p>
      <div class="table-wrap">
        <table id="tabel-peminjaman">
          <thead><tr><th>Buku</th><th>Anggota</th><th>No. HP</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody>
          <?php if (!$daftar): ?><tr><td colspan="8">Belum ada data.</td></tr><?php endif; ?>
          <?php foreach ($daftar as $r): ?>
          <tr>
            <td><?= e($r['judul']) ?></td>
            <td><?= e($r['nama_anggota']) ?></td>
            <td><?= e($r['no_hp'] ?? '-') ?></td>
            <td><?= e($r['tanggal_pinjam']) ?></td>
            <td><?= e($r['tanggal_jatuh_tempo']) ?></td>
            <td><?= e($r['tanggal_kembali'] ?? '-') ?></td>
            <td>
              <?php if ($r['status'] === 'dikembalikan'): ?>
                <span class="badge badge-selesai">dikembalikan</span>
              <?php elseif ($r['terlambat']): ?>
                <span class="badge badge-telat">terlambat</span>
              <?php else: ?>
                <span class="badge badge-dipinjam">dipinjam</span>
              <?php endif; ?>
            </td>
            <td class="aksi">
              <?php if ($r['status'] === 'dipinjam'): ?>
              <form action="kembali.php" method="post" class="inline-form form-kembali" data-pesan="Tandai buku ini sudah dikembalikan?">
                <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm">Kembalikan</button>
              </form>
              <?php else: ?>-<?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
