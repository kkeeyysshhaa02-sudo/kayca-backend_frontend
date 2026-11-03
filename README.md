# 📝 Website To-Do List Pribadi
**Tugas Proyek:** LKPD • XI RPL — DUO DEVELOPER  
**Konsep:** Kontrak dulu, ngoding bareng! (BE + Swagger × FE + Mock Data)  
**SMK PESAT • Rekayasa Perangkat Lunak**

---

## 👥 Tim Pengembang
- **Backend Developer (BE):** [Nama BE kamu] *(Kelas: XI RPL)*
- **Frontend Developer (FE):** [Nama FE teman kamu] *(Kelas: XI RPL)*
- **Tanggal:** November 2026

---

## 📌 Deskripsi Singkat
Aplikasi To-Do List pribadi berbasis web. Pengguna dapat melakukan registrasi, login, melihat daftar tugas miliknya, menambah tugas, mengubah judul/tenggat, menandai tugas selesai (`done`) atau belum selesai (`todo`), serta menghapus tugas. Sistem ini menerapkan keamanan autentikasi menggunakan **Laravel Sanctum**, di mana setiap pengguna hanya dapat melihat dan mengelola data tugas miliknya sendiri.

---

## 🛠️ Tech Stack
- **Backend:** Laravel 11 / 12, Laravel Sanctum (Token-based Auth), MySQL / SQLite
- **Dokumentasi API:** L5-Swagger (OpenAPI 3.0)
- **Frontend:** Blade Templating, JavaScript (Fetch API), CSS / Tailwind CSS

---

## ⚙️ Panduan Instalasi & Menjalankan Proyek

### 1. Clone Repository
```bash
git clone https://github.com/kkeeyysshhaa02-sudo/kayca-backend_frontend.git
cd kayca_backend_frontend
```

### 2. Konfigurasi Environment & Dependensi
Salin berkas `.env.example` ke `.env`:
```bash
copy .env.example .env
```
Install dependensi PHP & buat Application Key:
```bash
composer install
php artisan key:generate
```

### 3. Migrasi Database
Pastikan MySQL di Laragon / XAMPP sudah berjalan, lalu jalankan:
```bash
php artisan migrate
```

### 4. Generate Dokumentasi Swagger
```bash
php artisan l5-swagger:generate
```

### 5. Jalankan Server
```bash
php artisan serve
```
- Akses Aplikasi Web: `http://localhost:8000`
- Akses Dokumentasi Swagger UI: `http://localhost:8000/api/documentation`

---

## 📑 Kontrak API & Dokumentasi
- Dokumen kontrak API tertulis lengkap: [`docs/kontrak-api.md`](docs/kontrak-api.md)
- Spesifikasi OpenAPI JSON: [`storage/api-docs/api-docs.json`](storage/api-docs/api-docs.json)

### Ringkasan Endpoint API
| Method | Endpoint | Keterangan | Autentikasi |
|---|---|---|---|
| `POST` | `/api/register` | Mendaftarkan akun baru | Tidak |
| `POST` | `/api/login` | Masuk & mendapatkan Bearer Token | Tidak |
| `GET` | `/api/user` | Mendapatkan profil user yang sedang login | Bearer Token |
| `POST` | `/api/logout` | Mencabut token (keluar) | Bearer Token |
| `GET` | `/api/tasks` | Menampilkan seluruh tugas milik user login | Bearer Token |
| `POST` | `/api/tasks` | Menambahkan tugas baru | Bearer Token |
| `GET` | `/api/tasks/{id}` | Mengambil detail satu tugas | Bearer Token |
| `PUT` | `/api/tasks/{id}` | Memperbarui data tugas lengkap | Bearer Token |
| `PATCH` | `/api/tasks/{id}/status` | Mengubah cepat status (`todo` / `done`) | Bearer Token |
| `DELETE` | `/api/tasks/{id}` | Menghapus tugas | Bearer Token |

---

## 💡 Pertanyaan Evaluasi LKPD (Tahap 04)

### 1. Apa perbedaan Mock Data dan API Nyata?
- **Mock Data:** Data tiruan/dummy berformat JSON statis yang disiapkan Frontend berdasarkan kontrak API di awal. Mock data memungkinkan tim Frontend mulai mendesain tampilan dan logika fetch tanpa harus menunggu Backend selesai koding atau database terpasang.
- **API Nyata:** Layanan backend aktual yang terhubung ke server logic dan basis data MySQL. API ini memproses validasi nyata, autentikasi sesi/token, serta melakukan penyimpanan permanen (Create, Read, Update, Delete).

### 2. Perbedaan kontrak apa yang kalian temukan selama integrasi, dan bagaimana cara menyelesaikannya?
- **Temuan:** Di awal sempat ada perbedaan penggunaan istilah status tugas (antara `pending/completed` vs `todo/done`) dan penamaan field deadline (`due_date` vs `deadline`).
- **Solusi:** Kedua role (BE & FE) mengacu kembali pada kesepakatan tertulis di `docs/kontrak-api.md` yang menetapkan status seragam yaitu `"todo"` dan `"done"`, serta menambahkan endpoint khusus `PATCH /api/tasks/{id}/status` agar Frontend mudah membuat toggle checkbox tanpa harus mengirim ulang seluruh data tugas.

---

## 🚀 Alur Kerja Kolaborasi (Git Branching)
1. **Branch `feat/backend`:** Pengerjaan controller auth, controller task, migrasi database, dan anotasi Swagger.
2. **Branch `feat/frontend`:** Pengerjaan halaman Blade, fetch mock data, dan integrasi API asli.
3. **Pull Request & Review:** Kode masing-masing diajukan melalui Pull Request ke branch `main` dan direview oleh pasangan sebelum di-merge.

