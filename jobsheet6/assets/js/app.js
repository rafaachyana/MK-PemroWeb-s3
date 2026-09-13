// ===== Hamburger menu (JS-driven) =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

//4: Konfirmasi Hapus Menggunakan Event Delegation 
function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-hapus");
        if (!btn) return;

        const row = btn.closest("tr");
        const nama = row ? row.querySelector("td")?.textContent : "data ini";
        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
        if (yakin && row) {
            row.remove();
            if (typeof updateTableCounter === "function") {
                updateTableCounter();
            }
        }
    });
}

// ===== Filter Kolom Spesifik =====
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

// ===== Counter Baris Tabel =====
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

// ===== Helper Tampilan Error Form =====
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

function initValidasiForm() {
    const form = document.getElementById("form-tambah") || document.querySelector("main form");
    if (!form) return;
    form.addEventListener("submit", function (e) {
        let valid = true;
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
            {
                selector: "[name='isbn']",
                validate: (val) => {
                    if (val && !/^[0-9-]+$/.test(val)) {
                        return "ISBN hanya boleh berisi angka dan tanda hubung (-).";
                    }
                    return null;
                }
            }
        ];
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

//2: Fungsi Generik Fetch Data Asinkron
async function muatDaftarData(urlJson, keys) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");
    if (!tbody) return;

    if (loading) loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        //5: Simulasi Delay Jaringan (Async/Await & Promise) 
        await new Promise((resolve) => setTimeout(resolve, 3000));

        const res = await fetch(urlJson);
        if (!res.ok) {
            throw new Error("Gagal mengambil data (status " + res.status + ")");
        }
        const dataList = await res.json();

        dataList.forEach((item) => {
            const tr = document.createElement("tr");
            
            // Generate sel tabel (td) berdasarkan kunci properti yang dikirim
            let tdHTML = keys.map(key => `<td>${item[key] ?? '-'}</td>`).join("");
            
            // Tambahkan kolom Aksi
            tdHTML += `<td>
                <button type="button" class="btn-edit">Edit</button> 
                <button type="button" class="btn-hapus">Hapus</button>
            </td>`;
            
            tr.innerHTML = tdHTML;
            tbody.appendChild(tr);
        });
        
        if (typeof updateTableCounter === "function") {
            updateTableCounter();
        }
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="${keys.length + 1}">Gagal memuat data: ${err.message}</td></tr>`;
    } finally {
        if (loading) loading.style.display = "none";
    }
}

//1 & 3: Inisialisasi Aplikasi saat DOM Ready 
document.addEventListener("DOMContentLoaded", () => {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();

    // Memuat data buku beserta kunci 'kategori'
    muatDaftarData("../data/buku.json", ["judul", "pengarang", "tahun", "stok", "kategori"]);
    
    const btnReload = document.getElementById("btn-reload");
    if (btnReload) {
        btnReload.addEventListener("click", () => {
            muatDaftarData("../data/buku.json", ["judul", "pengarang", "tahun", "stok", "kategori"]);
        });
    }
});