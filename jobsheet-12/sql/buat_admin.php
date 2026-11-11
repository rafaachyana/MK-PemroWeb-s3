<?php
// Skrip CLI: membuat akun awal (admin & petugas) dengan password ter-hash.
// Pemakaian:  php sql/buat_admin.php                      -> admin/admin123 dan petugas/petugas123
//             php sql/buat_admin.php nama user password role
if (PHP_SAPI !== 'cli') {
    exit('Skrip ini hanya boleh dijalankan lewat command line.');
}
require_once __DIR__ . '/../includes/koneksi.php';

$akun = ($argc >= 5)
    ? [[$argv[1], $argv[2], $argv[3], $argv[4]]]
    : [['Administrator', 'admin', 'admin123', 'admin'], ['Petugas Contoh', 'petugas', 'petugas123', 'petugas']];

$stmt = $pdo->prepare(
    'INSERT INTO users (nama, username, password, role) VALUES (:n, :u, :p, :r)
     ON CONFLICT (username) DO NOTHING'
);
foreach ($akun as [$nama, $user, $pass, $role]) {
    if (!in_array($role, ['admin', 'petugas'], true)) {
        fwrite(STDERR, "Role tidak valid: $role\n");
        continue;
    }
    $stmt->execute([':n' => $nama, ':u' => $user, ':p' => password_hash($pass, PASSWORD_DEFAULT), ':r' => $role]);
    echo ($stmt->rowCount() ? "Dibuat   : " : "Sudah ada: ") . "$user ($role)\n";
}
