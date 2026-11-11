-- Data contoh (hanya dimasukkan bila tabel masih kosong)
INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
SELECT * FROM (VALUES
  ('Laskar Pelangi', 'Andrea Hirata', 2005, '9789793062792', 10, 'Fiksi'),
  ('Bumi Manusia', 'Pramoedya Ananta Toer', 1980, '9789799731234', 5, 'Fiksi'),
  ('Filosofi Teras', 'Henry Manampiring', 2018, '9786024125189', 7, 'Non-Fiksi'),
  ('Clean Code', 'Robert C. Martin', 2008, '9780132350884', 3, 'Teknologi'),
  ('Sapiens', 'Yuval Noah Harari', 2011, '9780062316097', 6, 'Sejarah'),
  ('Atomic Habits', 'James Clear', 2018, '9780735211292', 8, 'Non-Fiksi'),
  ('Laut Bercerita', 'Leila S. Chudori', 2017, '9786024246945', 4, 'Fiksi'),
  ('Pemrograman Web dengan PHP', 'Abdul Kadir', 2019, '9786232190123', 9, 'Teknologi'),
  ('Cosmos', 'Carl Sagan', 1980, '9780345539434', 2, 'Sains'),
  ('Negeri 5 Menara', 'Ahmad Fuadi', 2009, '9789792248074', 6, 'Fiksi'),
  ('Sang Pemimpi', 'Andrea Hirata', 2006, '9789793062808', 5, 'Fiksi'),
  ('A Brief History of Time', 'Stephen Hawking', 1988, '9780553380163', 3, 'Sains')
) AS v(judul, pengarang, tahun, isbn, stok, kategori)
WHERE NOT EXISTS (SELECT 1 FROM buku);

INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
SELECT * FROM (VALUES
  ('Budi Santoso', 'AG-001', 'Jl. Mawar No. 1, Surabaya', '081234567801'),
  ('Siti Aminah',  'AG-002', 'Jl. Melati No. 5, Sidoarjo', '081234567802'),
  ('Rafi Pratama', 'AG-003', 'Jl. Anggrek No. 9, Gresik', '081234567803'),
  ('Dewi Lestari', 'AG-004', 'Jl. Kenanga No. 12, Surabaya', '081234567804'),
  ('Andi Wijaya',  'AG-005', 'Jl. Dahlia No. 3, Malang', '081234567805')
) AS v(nama, no_anggota, alamat, no_hp)
WHERE NOT EXISTS (SELECT 1 FROM anggota);
