<?php
// 1. Mulai/akses sesi yang sedang berjalan
session_start();

// 2. Kosongkan semua variabel di dalam $_SESSION
session_unset();

// 3. Hancurkan data sesi di memori server
session_destroy();

// 4. Mulai sesi bersih yang baru untuk mengirimkan pesan flash
session_start();
$_SESSION['flash_success'] = "Seluruh data sesi berhasil dikosongkan!";

// 5. Kembalikan pengguna ke halaman debug_session.php
header("Location: debug_session.php");
exit;