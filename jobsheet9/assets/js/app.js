function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// Jobsheet 9: tombol Hapus kini berada di dalam <form class="form-hapus" method="POST">
// yang benar-benar mengirim request ke hapus.php. Konfirmasi dilakukan pada
// event "submit" supaya bisa dibatalkan dengan preventDefault().
function initHapusConfirm() {
    document.addEventListener("submit", function (e) {
        const form = e.target;
        if (!form.classList.contains("form-hapus")) return;

        const row = form.closest("tr");
        const nama = row ? row.querySelector("td").textContent.trim() : "data ini";
        if (!confirm('Yakin ingin menghapus "' + nama + '"?')) {
            e.preventDefault();
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initNavToggle();
    initHapusConfirm();
});
