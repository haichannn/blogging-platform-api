# 🚀 Blogging Platform API

Platform API yang dirancang untuk mendukung sistem manajemen blog modern, mengutamakan performa, keamanan, dan standar kode yang bersih.

*Project ini dikembangkan sebagai implementasi dari tantangan teknis **[roadmap.sh - Blogging Platform API](https://roadmap.sh/projects/blogging-platform-api)**.*

---

## 🎯 Tujuan Project

Project ini hadir sebagai fondasi backend yang solid untuk kebutuhan blogging. Tujuan utamanya adalah menyediakan API yang terstruktur, aman, dan mudah diintegrasikan oleh siapapun — baik aplikasi web maupun mobile. Dengan standar kode yang ketat dan dokumentasi yang jelas, project ini juga dirancang agar mudah dikembangkan lebih lanjut oleh tim manapun.

---

## ✨ Fitur Utama

- **📝 Manajemen Postingan** — Operasi lengkap CRUD (Create, Read, Update, Delete) untuk artikel blog.
- **🔍 Pencarian Postingan** — Filter artikel berdasarkan judul atau kategori menggunakan query parameter `?search=`.
- **📐 Standarisasi Respon** — Format JSON yang konsisten dan mudah dikonsumsi (key menggunakan camelCase).
- **⚡ Performa Tinggi** — Dioptimalkan untuk meminimalkan beban query ke database.
- **🔒 Keamanan** — Terlindungi dari SQL Injection dengan penggunaan Eloquent ORM dan query binding yang tepat.
- **📖 Dokumentasi API Interaktif** — Tersedia otomatis dan selalu up-to-date menggunakan Scramble.

---

## 🛠️ Tech Stack

| Komponen          | Teknologi                                              |
| ----------------- | ------------------------------------------------------ |
| Bahasa            | PHP 8.3                                                |
| Framework         | [Laravel](https://laravel.com/) 13.x                  |
| Database          | SQLite                                                 |
| Dokumentasi API   | [Scramble](https://scramble.dedoc.co/)                 |
| Code Style        | [Laravel Pint](https://laravel.com/docs/pint) (PSR-12) |
| Testing           | [PHPUnit](https://phpunit.de/) 12.x                   |

---

## ⚙️ Cara Instalasi

Pastikan sistem Anda sudah memiliki:
- **PHP** versi 8.3 atau lebih baru
- **Composer** versi terbaru
- **Node.js** & **npm** versi terbaru

### Langkah-langkah

**1. Clone repository ini**
```bash
git clone <url-repository>
```

**2. Masuk ke direktori project**
```bash
cd blogging-platform-api
```

**3. Jalankan perintah setup**
```bash
composer setup
```

Perintah ini akan menangani semua proses setup secara otomatis, mulai dari:
- Menginstal seluruh dependensi PHP (via Composer)
- Menyalin file `.env.example` menjadi `.env`
- Men-generate application key
- Menjalankan migrasi database
- Menginstal dependensi Node.js & membangun aset frontend

> Setelah langkah ini selesai, project sudah siap dijalankan!

---

## 🚀 Cara Menjalankan Project

Jalankan server development dengan perintah berikut:

```bash
composer dev
```

Perintah ini akan menjalankan beberapa proses sekaligus secara bersamaan:
- **Laravel Server** — Server API utama
- **Queue Listener** — Pemroses antrian background job
- **Pail (Log Viewer)** — Pemantau log secara real-time
- **Vite** — Kompilasi aset frontend


Server akan berjalan di: **`http://localhost:8000`**

---

## 📖 Cara Penggunaan API

Semua endpoint API tersedia di base URL: `http://localhost:8000/api`

### Dokumentasi API Interaktif

Project ini dilengkapi dengan dokumentasi API yang dibuat secara otomatis dan selalu sinkron dengan kode. Setelah server berjalan, buka browser Anda dan akses:

```
http://localhost:8000/docs/api
```

Di sana Anda bisa melihat seluruh daftar endpoint, format request, format response, dan langsung mencoba API-nya tanpa perlu tools tambahan.

### Ringkasan Endpoint

| Method   | Endpoint          | Deskripsi                              |
| -------- | ----------------- | -------------------------------------- |
| `GET`    | `/api/posts`      | Ambil semua postingan                  |
| `GET`    | `/api/posts?search={term}` | Cari postingan berdasarkan judul atau kategori |
| `POST`   | `/api/posts`      | Buat postingan baru                    |
| `GET`    | `/api/posts/{id}` | Ambil detail satu postingan            |
| `PATCH`  | `/api/posts/{id}` | Perbarui postingan (sebagian atau penuh) |
| `DELETE` | `/api/posts/{id}` | Hapus postingan                        |

### Contoh Request & Response

Berikut adalah contoh interaksi dengan API untuk membuat postingan baru:

**Request (HTTP / curl):**
```bash
curl -X POST http://localhost:8000/api/posts \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "Belajar Laravel 13",
    "content": "Laravel adalah framework PHP yang sangat menyenangkan.",
    "category": "Technology",
    "tags": ["Laravel", "PHP"]
  }'
```

**Response (201 Created):**
```json
{
  "id": 1,
  "title": "Belajar Laravel 13",
  "content": "Laravel adalah framework PHP yang sangat menyenangkan.",
  "category": "Technology",
  "tags": [
    "Laravel",
    "PHP"
  ],
  "createdAt": "2026-07-15T10:00:00Z",
  "updatedAt": "2026-07-15T10:00:00Z"
}
```

---

## 🧪 Menjalankan Tests

Untuk memastikan semua fitur berjalan dengan baik, jalankan perintah berikut:

```bash
composer test
```

---

## 🎨 Format Kode

Project ini menggunakan **Laravel Pint** untuk memastikan kode selalu rapi dan konsisten. Jalankan formatter dengan:

```bash
./vendor/bin/pint
```

