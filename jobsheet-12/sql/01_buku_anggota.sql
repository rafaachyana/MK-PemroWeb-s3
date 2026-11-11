-- Jobsheet 8: tabel buku & anggota (aman dijalankan berulang)
CREATE TABLE IF NOT EXISTS buku (
    id         SERIAL       PRIMARY KEY,
    judul      VARCHAR(200) NOT NULL,
    pengarang  VARCHAR(150) NOT NULL,
    tahun      INTEGER      NOT NULL CHECK (tahun BETWEEN 1000 AND 2100),
    isbn       VARCHAR(20),
    stok       INTEGER      NOT NULL DEFAULT 0 CHECK (stok >= 0),
    kategori   VARCHAR(50)  NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS anggota (
    id         SERIAL       PRIMARY KEY,
    nama       VARCHAR(120) NOT NULL,
    no_anggota VARCHAR(20)  NOT NULL UNIQUE,
    alamat     TEXT,
    no_hp      VARCHAR(20),
    email      VARCHAR(254),
    created_at TIMESTAMP    NOT NULL DEFAULT NOW()
);

-- Latihan Jobsheet 8: timestamp tambahan untuk audit sederhana.
ALTER TABLE buku ADD COLUMN IF NOT EXISTS tanggal_ditambahkan TIMESTAMP NOT NULL DEFAULT NOW();

ALTER TABLE anggota ADD COLUMN IF NOT EXISTS email VARCHAR(254);
