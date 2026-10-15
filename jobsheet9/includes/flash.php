<?php
// Menampilkan flash message (pola Jobsheet 7-8: $_SESSION['flash_success'] / ['flash_error']).
function tampil_flash(): void
{
    if (isset($_SESSION['flash_success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['flash_success'] . '</div>';
        unset($_SESSION['flash_success']);
    }
    if (isset($_SESSION['flash_error'])) {
        echo '<div class="alert alert-error">' . $_SESSION['flash_error'] . '</div>';
        unset($_SESSION['flash_error']);
    }
}
