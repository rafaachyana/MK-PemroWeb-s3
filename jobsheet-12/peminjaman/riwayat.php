<?php
$base = '../';
$judul_halaman = 'Riwayat Peminjaman';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';

$anggotaId = input_int($_GET, 'anggota_id');
$anggota = $pdo->query('SELECT id, nama, no_anggota FROM anggota ORDER BY nama')->fetchAll();

$riwayat = [];
if ($anggotaId !== null) {
    // JOIN 4 tabel: peminjaman + buku + anggota + users (petugas yang melayani)
    $stmt = $pdo->prepare(
        'SELECT p.tanggal_pinjam, p.tanggal_jatuh_tempo, p.tanggal_kembali, p.status,
                (p.status = \'dipinjam\' AND p.tanggal_pinjam < CURRENT_DATE - 14) AS terlambat,
                b.judul, a.nama AS nama_anggota, a.no_hp, u.nama AS nama_petugas
           FROM peminjaman p
           JOIN buku b    ON b.id = p.buku_id
           JOIN anggota a ON a.id = p.anggota_id
           LEFT JOIN users u ON u.id = p.petugas_id
          WHERE p.anggota_id = :a
          ORDER BY p.tanggal_pinjam DESC, p.id DESC'
    );
    $stmt->execute([':a' => $anggotaId]);
    $riwayat = $stmt->fetchAll();
}

require_once __DIR__ . '/../includes/header.php';
?>
    <section>
      <h1>Riwayat Peminjaman per Anggota</h1>
      <form method="get" class="form-cari">
        <label for="anggota_id">Anggota:</label>
        <select id="anggota_id" name="anggota_id">
          <option value="">-- Pilih anggota --</option>
          <?php foreach ($anggota as $a): ?>
            <option value="<?= (int) $a['id'] ?>"<?= $anggotaId === (int) $a['id'] ? ' selected' : '' ?>><?= e($a['nama']) ?> (<?= e($a['no_anggota']) ?>)</option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-sm">Tampilkan</button>
      </form>

      <?php if ($anggotaId !== null): ?>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Tgl Kembali</th><th>No. HP</th><th>Status</th><th>Dilayani</th></tr></thead>
          <tbody>
          <?php if (!$riwayat): ?><tr><td colspan="7">Anggota ini belum pernah meminjam.</td></tr><?php endif; ?>
          <?php foreach ($riwayat as $r): ?>
          <tr>
            <td><?= e($r['judul']) ?></td>
            <td><?= e($r['tanggal_pinjam']) ?></td>
            <td><?= e($r['tanggal_jatuh_tempo']) ?></td>
            <td><?= e($r['tanggal_kembali'] ?? '-') ?></td>
            <td><?= e($r['no_hp'] ?? '-') ?></td>
            <td><?= e($r['terlambat'] ? 'terlambat' : $r['status']) ?></td>
            <td><?= e($r['nama_petugas'] ?? '-') ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
