<?php
// Flash message sederhana berbasis $_SESSION (Jobsheet 7).
// $pesan boleh string atau array string (untuk daftar error validasi).
function set_flash(string $tipe, $pesan): void
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => (array) $pesan];
}

function tampil_flash(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    echo '<div class="alert alert-' . htmlspecialchars($f['tipe']) . '"><ul>';
    foreach ($f['pesan'] as $p) {
        echo '<li>' . htmlspecialchars($p) . '</li>';
    }
    echo '</ul></div>';
}
