<?php
// Guard clause (Jobsheet 10): include di baris paling atas setiap halaman terproteksi.
require_once __DIR__ . '/bootstrap.php';
if (empty($_SESSION['user_id'])) {
    header('Location: ' . ($base ?? '../') . 'auth/login.php');
    exit;
}

/** Batasi halaman untuk role tertentu (mis. hanya admin). */
function wajib_role(string ...$role): void
{
    if (!in_array($_SESSION['role'] ?? '', $role, true)) {
        http_response_code(403);
        exit('Akses ditolak: role Anda tidak diizinkan membuka halaman ini.');
    }
}
