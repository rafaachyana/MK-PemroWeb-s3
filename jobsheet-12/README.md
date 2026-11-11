# Jobsheet 12 - Integrasi: Peminjaman & Pengembalian

> Modul terakhir yang menyatukan buku, anggota, users + uji end-to-end.

Kelanjutan dari **Jobsheet 11**: seluruh berkas dibawa dan dikembangkan, bukan proyek baru.

## File Kunci

`peminjaman/{tambah,proses_tambah,kembali,list,riwayat}.php`, `sql/03_peminjaman.sql`, `docs/pengujian-e2e.md`

## Yang Dikerjakan

- Transaksi PDO (`beginTransaction/commit/rollBack`) + `SELECT ... FOR UPDATE`: stok tidak minus/dobel
- Dropdown buku hanya `stok > 0`
- Riwayat per anggota (JOIN 4 tabel)
- Statistik Beranda & Dashboard dari database (angka real)
- Tugas mandiri: anggota dengan pinjaman > 14 hari belum kembali tidak bisa meminjam

## Cara Menjalankan

Jalankan 4 file SQL (`01`-`04`) lalu `php sql/buat_admin.php`.

## Catatan

Hasil pengujian end-to-end: `docs/pengujian-e2e.md`.


### Catatan latihan CSRF pada pencarian GET
Form pencarian `GET` hanya membaca/filter data dan URL dapat dibagikan/bookmark, sehingga umumnya tidak membutuhkan token CSRF. Perubahan data harus memakai `POST` (atau metode mutasi) dengan token CSRF, sebab browser dapat mengirim permintaan lintas situs yang memicu aksi tanpa persetujuan pengguna. `GET` tidak boleh mengubah state.
