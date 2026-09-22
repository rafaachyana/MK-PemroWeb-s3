<?php
session_start();
$title = "SIMPUS-Mini | Debug Session";
$base_path = "./";

include_once "includes/header.php";
?>

<section>
    <h2>Debug Data Sesi ($_SESSION)</h2>
    <p>Halaman ini digunakan untuk mengintip struktur data yang tersimpan di dalam sesi server saat ini.</p>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem;">
            <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <!--4. Tombol Reset Data Sesi -->
    <div style="margin-bottom: 1rem;">
        <a href="reset_session.php" onclick="return confirm('Apakah Anda yakin ingin menghapus seluruh data sesi?')" style="background-color: #d9534f; color: #fff; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; display: inline-block;">
            🗑️ Reset Data Sesi (session_destroy)
        </a>
    </div>

    <!--3. Menampilkan isi $_SESSION mentah -->
    <h3>Isi variabel $_SESSION:</h3>
    <pre style="background-color: #272822; color: #f8f8f2; padding: 1rem; border-radius: 6px; overflow-x: auto; font-family: monospace;"><?php 
        if (!empty($_SESSION)) {
            print_r($_SESSION); 
        } else {
            echo "Session saat ini KOSONG.";
        }
    ?></pre>
</section>

<?php include_once "includes/footer.php"; ?>