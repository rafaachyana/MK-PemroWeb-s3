<?php
// Helper keamanan (Jobsheet 11): session aman, escape output, CSRF, sanitasi input.

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,          // cookie tidak terbaca JavaScript
        'samesite' => 'Lax',         // mitigasi tambahan CSRF
        'secure'   => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}

/** Escape output ke HTML (cegah XSS). */
function e($nilai): string
{
    return htmlspecialchars((string) $nilai, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Token CSRF per-session. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/** Hentikan request POST bila token tidak cocok. */
function csrf_verify(): void
{
    $kirim = $_POST['csrf_token'] ?? '';
    if (!is_string($kirim) || !hash_equals($_SESSION['csrf_token'] ?? '', $kirim)) {
        http_response_code(403);
        exit('Permintaan ditolak: token CSRF tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}

/** Ambil string dari $_POST/$_GET: trim + batasi panjang. */
function input_str(array $sumber, string $kunci, int $maks = 255): string
{
    $v = $sumber[$kunci] ?? '';
    if (!is_string($v)) {
        return '';
    }
    $v = trim($v);
    // mb_substr bila ekstensi mbstring aktif (aman untuk huruf non-ASCII), selain itu substr biasa
    return function_exists('mb_substr') ? mb_substr($v, 0, $maks) : substr($v, 0, $maks);
}

/** Ambil integer, null bila bukan bilangan bulat valid. */
function input_int(array $sumber, string $kunci): ?int
{
    $v = $sumber[$kunci] ?? null;
    $hasil = filter_var($v, FILTER_VALIDATE_INT);
    return $hasil === false ? null : $hasil;
}
