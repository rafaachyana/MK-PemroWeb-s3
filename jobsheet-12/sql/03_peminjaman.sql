-- Jobsheet 12: tabel peminjaman (menghubungkan buku, anggota, users)
CREATE TABLE IF NOT EXISTS peminjaman (
    id              SERIAL      PRIMARY KEY,
    buku_id         INTEGER     NOT NULL REFERENCES buku(id)    ON DELETE RESTRICT,
    anggota_id      INTEGER     NOT NULL REFERENCES anggota(id) ON DELETE RESTRICT,
    petugas_id      INTEGER              REFERENCES users(id)   ON DELETE SET NULL,
    tanggal_pinjam  DATE        NOT NULL DEFAULT CURRENT_DATE,
    tanggal_jatuh_tempo DATE NOT NULL DEFAULT (CURRENT_DATE + 14),
    tanggal_kembali DATE,
    status          VARCHAR(15) NOT NULL DEFAULT 'dipinjam' CHECK (status IN ('dipinjam', 'dikembalikan'))
);
CREATE INDEX IF NOT EXISTS idx_peminjaman_status  ON peminjaman (status);
CREATE INDEX IF NOT EXISTS idx_peminjaman_anggota ON peminjaman (anggota_id);
CREATE INDEX IF NOT EXISTS idx_peminjaman_buku    ON peminjaman (buku_id);

-- Migrasi aman untuk database yang tabel peminjamannya sudah dibuat sebelumnya.
ALTER TABLE peminjaman ADD COLUMN IF NOT EXISTS tanggal_jatuh_tempo DATE NOT NULL DEFAULT (CURRENT_DATE + 14);
UPDATE peminjaman SET tanggal_jatuh_tempo = tanggal_pinjam + 14 WHERE tanggal_jatuh_tempo IS NULL OR tanggal_jatuh_tempo <> tanggal_pinjam + 14;
