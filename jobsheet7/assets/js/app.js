// ===== Hamburger Menu Toggle =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== Konfirmasi Hapus Baris Tabel =====
function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-delete");
        if (!btn) return;

        const row = btn.closest("tr");
        const nama = row ? row.querySelector("td")?.textContent : "data ini";
        const yakin = confirm('Yakin ingin menghapus "' + nama.trim() + '"?');
        if (yakin && row) {
            row.remove();
            updateTableCounter();
        }
    });
}

// ===== Filter Pencarian Tabel Instan =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!table || !input) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");

        rows.forEach(function (row) {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(keyword) ? "" : "none";
        });
        updateTableCounter();
    });
}

// ===== Hitung Jumlah Baris Tabel =====
function updateTableCounter() {
    const table = document.querySelector(".table-responsive table");
    const counterElement = document.getElementById("table-counter");
    if (!table || !counterElement) return;

    const rows = table.querySelectorAll("tbody tr");
    const totalRows = rows.length;
    const visibleRows = Array.from(rows).filter(row => row.style.display !== "none").length;
    const isBuku = document.title.toLowerCase().includes("buku");
    const labelItem = isBuku ? "buku" : "data";

    if (totalRows === 0) {
        counterElement.textContent = `Belum ada ${labelItem}.`;
    } else {
        counterElement.textContent = `Menampilkan ${visibleRows} dari ${totalRows} ${labelItem}`;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    updateTableCounter();
});