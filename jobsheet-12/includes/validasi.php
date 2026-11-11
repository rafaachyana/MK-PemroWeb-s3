<?php
// Validasi & sanitasi input terpusat (Jobsheet 11). Mengembalikan [data_bersih, daftar_error].
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/konstanta.php';

function validasi_buku(array $in): array
{
    $tahunMaks = (int) date('Y') + 1;
    $d = [
        'judul'     => input_str($in, 'judul', 200),
        'pengarang' => input_str($in, 'pengarang', 150),
        'tahun'     => input_int($in, 'tahun'),
        'isbn'      => input_str($in, 'isbn', 20),
        'stok'      => input_int($in, 'stok'),
        'kategori'  => input_str($in, 'kategori', 50),
    ];
    $err = [];
    if ($d['judul'] === '')     { $err[] = 'Judul wajib diisi.'; }
    if ($d['pengarang'] === '') { $err[] = 'Pengarang wajib diisi.'; }
    if ($d['tahun'] === null || $d['tahun'] < 1000 || $d['tahun'] > $tahunMaks) {
        $err[] = "Tahun harus berupa angka antara 1000 dan $tahunMaks.";
    }
    if ($d['isbn'] !== '' && !preg_match('/^[0-9Xx-]{10,20}$/', $d['isbn'])) {
        $err[] = 'ISBN hanya boleh berisi angka, tanda hubung, atau X (10-20 karakter).';
    }
    if ($d['stok'] === null || $d['stok'] < 0 || $d['stok'] > 100000) {
        $err[] = 'Stok harus bilangan bulat antara 0 dan 100000.';
    }
    if (!in_array($d['kategori'], KATEGORI_BUKU, true)) {   // whitelist
        $err[] = 'Kategori tidak valid.';
    }
    return [$d, $err];
}

function validasi_anggota(array $in): array
{
    $d = [
        'nama'       => input_str($in, 'nama', 120),
        'no_anggota' => strtoupper(input_str($in, 'no_anggota', 20)),
        'alamat'     => input_str($in, 'alamat', 500),
        'no_hp'      => input_str($in, 'no_hp', 15),
    ];
    $err = [];
    if ($d['nama'] === '') { $err[] = 'Nama wajib diisi.'; }
    if (!preg_match('/^[A-Z]{2}-\d{3,10}$/', $d['no_anggota'])) {
        $err[] = 'Format no. anggota harus seperti AG-001.';
    }
    if ($d['no_hp'] !== '' && !preg_match('/^[0-9+]{8,15}$/', $d['no_hp'])) {
        $err[] = 'No. HP hanya berisi angka (8-15 digit).';
    }
    return [$d, $err];
}
