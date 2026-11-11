/* =====================================================
   SIMPUS-Mini - app.js
   Jobsheet 5: DOM & event (hamburger, validasi, filter, konfirmasi hapus)
   (dipakai ulang di Jobsheet 7+, form divalidasi lalu dikirim ke PHP)
   ===================================================== */
'use strict';

/* ---------- 1. Hamburger menu (menggantikan checkbox hack Jobsheet 3) ---------- */
const menuBtn = document.querySelector('#menu-btn');
const mainNav = document.querySelector('#main-nav');
if (menuBtn && mainNav) {
  menuBtn.addEventListener('click', () => {
    const terbuka = mainNav.classList.toggle('open');
    menuBtn.setAttribute('aria-expanded', terbuka);
  });
}

/* ---------- 2. Validasi form (client-side) ---------- */
function tampilkanError(input, pesan) {
  hapusError(input);
  input.classList.add('invalid');
  const span = document.createElement('span');
  span.className = 'error';
  span.textContent = pesan;
  input.insertAdjacentElement('afterend', span);
}
function hapusError(input) {
  input.classList.remove('invalid');
  const next = input.nextElementSibling;
  if (next && next.classList.contains('error')) next.remove();
}
function tampilkanSukses(form, pesan) {
  form.parentElement.querySelectorAll('.sukses-box').forEach(el => el.remove());
  const box = document.createElement('div');
  box.className = 'sukses-box';
  box.textContent = pesan;
  form.insertAdjacentElement('beforebegin', box);
}

/* aturan: { namaField: fungsi(nilai) -> pesan error atau '' } */
function pasangValidasi(form, aturan) {
  if (!form) return;
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    let valid = true;
    for (const [nama, cek] of Object.entries(aturan)) {
      const input = form.elements[nama];
      if (!input) continue;
      const pesan = cek(input.value.trim());
      if (pesan) { tampilkanError(input, pesan); valid = false; }
      else hapusError(input);
    }
    if (valid) {
      const action = form.getAttribute('action');
      if (action && action !== '#') {
        form.submit();               // Jobsheet 7+: lanjutkan ke server (PHP)
      } else {
        tampilkanSukses(form, 'Data valid. (Belum dikirim ke server - pemrosesan PHP dimulai Jobsheet 7.)');
        form.reset();
      }
    }
  });
  form.addEventListener('input', (e) => hapusError(e.target));
}

const wajib = (label) => (v) => v === '' ? `${label} wajib diisi.` : '';

pasangValidasi(document.querySelector('#form-buku'), {
  judul: wajib('Judul'),
  pengarang: wajib('Pengarang'),
  tahun: (v) => {
    if (v === '') return 'Tahun wajib diisi.';
    const t = Number(v);
    const sekarang = new Date().getFullYear();
    return (!Number.isInteger(t) || t < 1000 || t > sekarang + 1)
      ? `Tahun harus antara 1000 dan ${sekarang + 1}.` : '';
  },
  stok: (v) => (v === '' || !Number.isInteger(Number(v)) || Number(v) < 0) ? 'Stok harus bilangan bulat >= 0.' : '',
  kategori: wajib('Kategori'),
});

pasangValidasi(document.querySelector('#form-anggota'), {
  nama: wajib('Nama'),
  no_anggota: (v) => v === '' ? 'No. anggota wajib diisi.' : (/^[A-Za-z]{2}-\d{3,}$/.test(v) ? '' : 'Format harus seperti AG-001.'),
  no_hp: (v) => (v !== '' && !/^[0-9+]{8,15}$/.test(v)) ? 'No. HP hanya angka (8-15 digit).' : '',
});

pasangValidasi(document.querySelector('#form-login'), {
  username: wajib('Username'),
  password: (v) => v.length < 6 ? 'Password minimal 6 karakter.' : '',
});

/* ---------- 3. Filter pencarian real-time ---------- */
const inputCari = document.querySelector('#cari');
if (inputCari) {
  const kolom = Number(inputCari.dataset.kolom || 0);
  inputCari.addEventListener('keyup', () => {
    const kata = inputCari.value.toLowerCase();
    document.querySelectorAll('table tbody tr').forEach((tr) => {
      const teks = (tr.cells[kolom]?.textContent || '').toLowerCase();
      tr.style.display = teks.includes(kata) ? '' : 'none';
    });
  });
}

/* ---------- 4. Konfirmasi sebelum aksi yang mengubah data (Jobsheet 9+) ---------- */
document.querySelectorAll('form.form-hapus, form.form-kembali').forEach((form) => {
  form.addEventListener('submit', (e) => {
    const pesan = form.dataset.pesan || 'Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
    if (!confirm(pesan)) e.preventDefault();
  });
});

/* ---------- 5. Validasi form registrasi (Jobsheet 10) ---------- */
pasangValidasi(document.querySelector('#form-register'), {
  nama: wajib('Nama'),
  username: (v) => /^[a-zA-Z0-9_]{3,30}$/.test(v) ? '' : 'Username 3-30 karakter: huruf, angka, garis bawah.',
  password: (v) => v.length < 8 ? 'Password minimal 8 karakter.' : '',
  konfirmasi: (v) => v !== (document.querySelector('#password')?.value || '') ? 'Konfirmasi password tidak cocok.' : '',
});

/* ---------- 6. Validasi form peminjaman (Jobsheet 12) ---------- */
pasangValidasi(document.querySelector('#form-pinjam'), {
  anggota_id: wajib('Anggota'),
  buku_id: wajib('Buku'),
});

document.querySelectorAll('.form-konfirmasi-update').forEach((form) => {
  form.addEventListener('submit', (event) => {
    if (!window.confirm('Simpan perubahan data ini?')) event.preventDefault();
  });
});
