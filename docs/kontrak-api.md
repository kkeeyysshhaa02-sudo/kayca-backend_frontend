# Kontrak API - Website To-Do List Pribadi
**Proyek:** Duo Developer (BE + Swagger × FE + Mock Data)  
**Base URL:** `http://localhost:8000/api` atau `http://127.0.0.1:8000/api`  
**Header Wajib:**
```http
Accept: application/json
Content-Type: application/json
```
Untuk endpoint terlindungi, sertakan header:
```http
Authorization: Bearer <token_kamu>
```

---

## 1. Autentikasi (Auth - Sanctum)

### 1.1 Register Pengguna Baru
- **Method:** `POST`
- **URL:** `/register`
- **Auth:** Tidak

**Request Body:**
```json
{
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response Sukses (`201 Created`):**
```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Budi Santoso",
      "email": "budi@example.com",
      "created_at": "2026-11-03T05:00:00.000000Z",
      "updated_at": "2026-11-03T05:00:00.000000Z"
    },
    "token": "1|abcdef1234567890..."
  }
}
```

**Response Gagal Validasi (`422 Unprocessable Entity`):**
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": [
      "The email has already been taken."
    ]
  }
}
```

---

### 1.2 Login Pengguna
- **Method:** `POST`
- **URL:** `/login`
- **Auth:** Tidak

**Request Body:**
```json
{
  "email": "budi@example.com",
  "password": "password123"
}
```

**Response Sukses (`200 OK`):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Budi Santoso",
      "email": "budi@example.com",
      "created_at": "2026-11-03T05:00:00.000000Z",
      "updated_at": "2026-11-03T05:00:00.000000Z"
    },
    "token": "2|fedcba0987654321..."
  }
}
```

**Response Gagal Kredensial (`401 Unauthorized`):**
```json
{
  "success": false,
  "message": "Email atau password salah"
}
```

---

### 1.3 Profil Pengguna (User Info)
- **Method:** `GET`
- **URL:** `/user` atau `/profile`
- **Auth:** Ya (`Bearer Token`)

**Response Sukses (`200 OK`):**
```json
{
  "success": true,
  "message": "Data profil user berhasil diambil",
  "data": {
    "id": 1,
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "created_at": "2026-11-03T05:00:00.000000Z",
    "updated_at": "2026-11-03T05:00:00.000000Z"
  }
}
```

---

### 1.4 Logout
- **Method:** `POST`
- **URL:** `/logout`
- **Auth:** Ya (`Bearer Token`)

**Response Sukses (`200 OK`):**
```json
{
  "success": true,
  "message": "Logout berhasil, token telah dicabut"
}
```

---

## 2. To-Do List (Tasks)

Setiap akun pengguna hanya dapat melihat dan mengelola tugas miliknya sendiri.

### Atribut Model Task:
- `id` (integer) - ID unik
- `user_id` (integer) - Relasi pemilik tugas
- `title` (string, wajib) - Judul tugas
- `description` (text/string, opsional/nullable) - Deskripsi tugas
- `status` (string, `todo` atau `done`, default: `todo`)
- `due_date` (date format `YYYY-MM-DD`, opsional/nullable) - Tenggat waktu
- `created_at`, `updated_at` (timestamp)

---

### 2.1 Ambil Semua Tugas Pengguna
- **Method:** `GET`
- **URL:** `/tasks`
- **Auth:** Ya (`Bearer Token`)

**Response Sukses (`200 OK`):**
```json
{
  "success": true,
  "message": "Daftar tugas berhasil ditampilkan",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "title": "Mengerjakan LKPD Duo Developer",
      "description": "API Laravel + L5-Swagger",
      "status": "todo",
      "due_date": "2026-11-05",
      "created_at": "2026-11-03T05:10:00.000000Z",
      "updated_at": "2026-11-03T05:10:00.000000Z"
    }
  ]
}
```

---

### 2.2 Tambah Tugas Baru
- **Method:** `POST`
- **URL:** `/tasks`
- **Auth:** Ya (`Bearer Token`)

**Request Body:**
```json
{
  "title": "Belajar Laravel Sanctum",
  "description": "Membaca dokumentasi Sanctum",
  "status": "todo",
  "due_date": "2026-11-10"
}
```

**Response Sukses (`201 Created`):**
```json
{
  "success": true,
  "message": "Tugas berhasil ditambahkan",
  "data": {
    "id": 2,
    "user_id": 1,
    "title": "Belajar Laravel Sanctum",
    "description": "Membaca dokumentasi Sanctum",
    "status": "todo",
    "due_date": "2026-11-10",
    "created_at": "2026-11-03T05:15:00.000000Z",
    "updated_at": "2026-11-03T05:15:00.000000Z"
  }
}
```

---

### 2.3 Detail Satu Tugas
- **Method:** `GET`
- **URL:** `/tasks/{id}`
- **Auth:** Ya (`Bearer Token`)

**Response Sukses (`200 OK`):**
```json
{
  "success": true,
  "message": "Detail tugas berhasil diambil",
  "data": {
    "id": 2,
    "user_id": 1,
    "title": "Belajar Laravel Sanctum",
    "description": "Membaca dokumentasi Sanctum",
    "status": "todo",
    "due_date": "2026-11-10",
    "created_at": "2026-11-03T05:15:00.000000Z",
    "updated_at": "2026-11-03T05:15:00.000000Z"
  }
}
```

---

### 2.4 Update Tugas Lengkap
- **Method:** `PUT` / `PATCH`
- **URL:** `/tasks/{id}`
- **Auth:** Ya (`Bearer Token`)

**Request Body:**
```json
{
  "title": "Belajar Laravel Sanctum & Swagger",
  "description": "Dokumentasi Sanctum dan OpenAPI",
  "status": "done",
  "due_date": "2026-11-12"
}
```

**Response Sukses (`200 OK`):**
```json
{
  "success": true,
  "message": "Tugas berhasil diperbarui",
  "data": {
    "id": 2,
    "user_id": 1,
    "title": "Belajar Laravel Sanctum & Swagger",
    "description": "Dokumentasi Sanctum dan OpenAPI",
    "status": "done",
    "due_date": "2026-11-12",
    "created_at": "2026-11-03T05:15:00.000000Z",
    "updated_at": "2026-11-03T05:25:00.000000Z"
  }
}
```

---

### 2.5 Toggle / Ubah Cepat Status Tugas
- **Method:** `PATCH`
- **URL:** `/tasks/{id}/status`
- **Auth:** Ya (`Bearer Token`)

**Request Body:**
```json
{
  "status": "done"
}
```

**Response Sukses (`200 OK`):**
```json
{
  "success": true,
  "message": "Status tugas berhasil diperbarui",
  "data": {
    "id": 2,
    "status": "done"
  }
}
```

---

### 2.6 Hapus Tugas
- **Method:** `DELETE`
- **URL:** `/tasks/{id}`
- **Auth:** Ya (`Bearer Token`)

**Response Sukses (`200 OK`):**
```json
{
  "success": true,
  "message": "Tugas berhasil dihapus"
}
```

---

## 3. Catatan Untuk Frontend (Mock Data)
Frontend dapat langsung mengacu pada format JSON di atas untuk membuat Mock Data / fungsi fetch dummy sebelum API Backend siap digunakan sepenuhnya.
Format status yang disepakati:
- `"todo"` : Belum Selesai
- `"done"` : Selesai