// ===== Hamburger menu (JS-driven, menggantikan checkbox hack) =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== Konfirmasi hapus (front-end only, belum ke server) =====
function initHapusConfirm() {
    document.querySelectorAll(".btn-hapus").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const row = btn.closest("tr");
            const nama = row ? row.querySelector("td")?.textContent : "data ini";
            const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
            if (yakin && row) {
                row.remove();
            }
        });
    });
}

// ===== 3. Filter Kolom Spesifik & Counter =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!table) return;
    if (input) {
        input.addEventListener("keyup", function () {
            const keyword = input.value.toLowerCase();
            const rows = table.querySelectorAll("tbody tr");
            rows.forEach(function (row) {
                const firstCell = row.querySelector("td");
                const teks = firstCell ? firstCell.textContent.toLowerCase() : "";
                row.style.display = teks.includes(keyword) ? "" : "none";
            });

            updateTableCounter(); 
        });
    }
    updateTableCounter();
}

// ===== 4. Counter Baris Tabel =====
function updateTableCounter() {
    const table = document.querySelector(".table-responsive table");
    const counterElement = document.getElementById("table-counter");
    if (!table || !counterElement) return;
    const rows = table.querySelectorAll("tbody tr");
    const totalRows = rows.length;
    const visibleRows = Array.from(rows).filter(row => row.style.display !== "none").length;
    const isBuku = document.title.toLowerCase().includes("buku");
    const labelItem = isBuku ? "buku" : "data";
    counterElement.textContent = `Menampilkan ${visibleRows} dari ${totalRows} ${labelItem}`;
}

// ===== Validasi form (client-side) =====
function tampilkanError(input, pesan) {
    hapusError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.textContent = pesan;
    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

// ===== 5: Refactor Validasi Form Menggunakan Array & forEach =====
function initValidasiForm() {
    const form = document.getElementById("form-tambah") || document.querySelector("main form");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;

        // Daftar aturan validasi field dikemas ke dalam array
        const rules = [
            { 
                selector: "[name='judul'], [name='nama']", 
                required: true, 
                pesanWajib: "Field ini wajib diisi." 
            },
            { 
                selector: "[name='pengarang']", 
                required: true, 
                pesanWajib: "Pengarang wajib diisi." 
            },
            { 
                selector: "[name='tahun']", 
                validate: (val) => {
                    if (!val) return null;
                    const nilai = parseInt(val, 10);
                    return (isNaN(nilai) || nilai < 1900 || nilai > 2026) 
                        ? "Tahun harus di antara 1900-2026." 
                        : null;
                } 
            },
            { 
                selector: "[name='stok']", 
                validate: (val) => {
                    if (!val) return null;
                    const nilai = parseInt(val, 10);
                    return (isNaN(nilai) || nilai < 0) 
                        ? "Stok tidak boleh negatif." 
                        : null;
                } 
            },
            // ===== 1: VALIDASI FIELD ISBN (ANGKA & STRIP) =====
            {
                selector: "[name='isbn']",
                validate: (val) => {
                    // Hanya menerima angka dan tanda hubung (-), opsional jika diisi
                    if (val && !/^[0-9-]+$/.test(val)) {
                        return "ISBN hanya boleh berisi angka dan tanda hubung (-).";
                    }
                    return null;
                }
            }
        ];
        // 5: Perulangan forEach untuk memvalidasi setiap aturan pada array rules
        rules.forEach(rule => {
            const input = form.querySelector(rule.selector);
            if (!input) return; 
            const value = input.value.trim();
            let errorPesan = null;
            if (rule.required && value === "") {
                errorPesan = rule.pesanWajib;
            } else if (rule.validate) {
                errorPesan = rule.validate(value);
            }
            if (errorPesan) {
                tampilkanError(input, errorPesan);
                valid = false;
            } else {
                hapusError(input);
            }
        });
        if (!valid) {
            e.preventDefault();
        }
    });
}
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});