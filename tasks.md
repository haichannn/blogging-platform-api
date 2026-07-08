# Task List: Blogging Platform API

File ini adalah panduan **step-by-step** yang harus diikuti oleh programmer junior dan AI model saat mengerjakan project ini. Setiap task harus dikerjakan secara berurutan dari atas ke bawah.

> **Sebelum mulai:** Pastikan kamu sudah membaca `AGENTS.md`, `PRD.md`, dan semua file di folder `rules/`.

---

## Cara Menggunakan File Ini

### Status Task
Tandai setiap task dengan status berikut:
- `[ ]` — Belum dikerjakan.
- `[x]` — Sudah selesai.

> **Catatan:** Untuk task yang sedang dikerjakan, cukup biarkan statusnya `[ ]`. Branch dan Pull Request yang terbuka sudah menjadi indikator bahwa task tersebut sedang dalam pengerjaan.

### Alur Kerja (Workflow) Setiap Task
1. **Baca task** ini dan tentukan task mana yang akan dikerjakan selanjutnya.
2. **Buat GitHub Issue** untuk task tersebut. Jelaskan apa yang akan dikerjakan, referensikan bagian PRD yang relevan.
3. **Buat branch baru** dari `main` sesuai aturan di `rules/convention-semantic-git.md`.
4. **Implementasi kode** sesuai PRD dan rules. Kerjakan semua sub-task di dalam satu branch yang sama.
5. **Jalankan test** untuk memastikan tidak ada yang rusak (`composer test`).
6. **Jalankan Pint** untuk memformat kode sesuai standar (`./vendor/bin/pint`).
7. **Commit & Push** ke GitHub hanya ketika seluruh sub-task dalam satu task sudah selesai dan siap di-review. Jangan commit setiap kali membuat file baru — kumpulkan semua perubahan terkait task tersebut dalam satu commit yang bermakna, lalu push sekaligus.
8. **Buat Pull Request** ke branch `main` untuk di-review. Tulis deskripsi PR yang jelas: jelaskan **apa** yang dikerjakan, **mengapa** perubahan ini dibuat, dan **bagaimana** cara kerjanya. Sertakan referensi ke GitHub Issue terkait agar mudah dilacak di kemudian hari.
9. **Setelah di-merge**, update status task di file ini menjadi `[x]`.

---

## Task 1: Foundation — Migration, Model, Factory & Seeder

- [ ] Buat migration untuk tabel `posts` sesuai `PRD.md` → bagian **Database Design**.
- [ ] Buat model `Post` dengan `$fillable` dan `$casts` (kolom `tags` di-cast ke `array`).
- [ ] Buat `PostFactory` untuk menghasilkan data dummy post yang realistis.
- [ ] Buat `PostSeeder` untuk mengisi tabel `posts` dengan data dummy menggunakan factory.
- [ ] Jalankan migration dan seeder (`php artisan migrate --seed`).

**Branch:** `feat/post-model-and-migration`
**Files:**
- `database/migrations/xxxx_xx_xx_create_posts_table.php`
- `app/Models/Post.php`
- `database/factories/PostFactory.php`
- `database/seeders/PostSeeder.php`
---

## Task 2: API Resource & Form Requests

- [ ] Buat `PostResource` (Eloquent API Resource) untuk transformasi key snake_case → camelCase.
- [ ] Buat `StorePostRequest` (FormRequest) untuk validasi Create.
- [ ] Buat `UpdatePostRequest` (FormRequest) untuk validasi Update.

**Referensi:** `PRD.md` → bagian **Validation Rules** dan **Response JSON Key Format**.
**Branch:** `feat/post-resource-and-requests`
**Files:**
- `app/Http/Resources/PostResource.php`
- `app/Http/Requests/StorePostRequest.php`
- `app/Http/Requests/UpdatePostRequest.php`

---

## Task 3: Controller & Routes

- [ ] Buat `PostController` dengan method: `index`, `store`, `show`, `update`, `destroy`.
- [ ] Method `index` harus menangani fitur search via query parameter `?search=`.
- [ ] Daftarkan API routes di `routes/api.php`.

**Referensi:** `PRD.md` → bagian **API Endpoints**.
**Patuhi:** `rules/api-rule.md`, `rules/performance-rule.md`, `rules/security-sql-injection.md`.
**Branch:** `feat/post-controller-and-routes`
**Files:**
- `app/Http/Controllers/PostController.php`
- `routes/api.php`

---

## Task 4: Feature Tests

- [ ] Test Create Post (`POST /posts`) — sukses (201), validasi gagal (422).
- [ ] Test Update Post (`PATCH /posts/{id}`) — sukses full update (200), sukses partial update/hanya sebagian field (200), tidak ditemukan (404), validasi gagal (422).
- [ ] Test Delete Post (`DELETE /posts/{id}`) — sukses (204), tidak ditemukan (404).
- [ ] Test Get Single Post (`GET /posts/{id}`) — sukses (200), tidak ditemukan (404).
- [ ] Test Get All Posts (`GET /posts`) — ada data (200), data kosong (200 dengan array kosong).
- [ ] Test Search Posts (`GET /posts?search=`) — ditemukan (200), tidak ditemukan (200 dengan array kosong).

**Wajib:** Semua test method menggunakan `#[TestDox('...')]` attribute (lihat `AGENTS.md`).
**Patuhi:** `rules/api-rule.md` — pastikan test meng-assert format response sukses (tanpa wrapper, key camelCase) dan format error (`"errors"` key pada 422/404).
**Branch:** `feat/post-feature-tests`
**Files:**
- `tests/Feature/PostCreateTest.php`
- `tests/Feature/PostUpdateTest.php`
- `tests/Feature/PostDeleteTest.php`
- `tests/Feature/PostShowTest.php`
- `tests/Feature/PostIndexTest.php`
- `tests/Feature/PostSearchTest.php`

---

## Catatan Penting

- **Jangan pernah** coding langsung di branch `main` (lihat `rules/convention-semantic-git.md`).
- **Jangan pernah** skip membaca file di folder `rules/` sebelum implementasi.
- **Jangan commit** setiap kali membuat file baru. Commit hanya ketika semua sub-task dalam satu task sudah selesai.
- **Selalu** jalankan `composer test` sebelum push.
- **Selalu** tulis PHPDoc comment di setiap method (lihat `AGENTS.md`).
- **Selalu** buat GitHub Issue terlebih dahulu sebelum mulai coding sebuah task.

---

## Catatan Khusus untuk AI Model

> **Penting:** Instruksi ini berlaku untuk semua AI model, terutama model yang lebih kecil/murah yang memiliki keterbatasan pengetahuan bawaan.

- **Selalu gunakan Context7 MCP** (`context7-mcp`) untuk mengambil dokumentasi terbaru sebelum mengimplementasikan kode. Jangan mengandalkan pengetahuan bawaan — dokumentasi library bisa berubah sewaktu-waktu.
- **Alur wajib sebelum coding:**
  1. Baca `AGENTS.md` untuk memahami instruksi penggunaan Context7 MCP dan aturan project.
  2. Gunakan `resolve-library-id` di Context7 MCP untuk mencari library yang akan digunakan (contoh: Laravel, PHPUnit, dll).
  3. Gunakan `query-docs` dengan pertanyaan yang spesifik per konsep untuk mendapatkan dokumentasi yang akurat.
  4. Baru mulai implementasi berdasarkan dokumentasi yang sudah diambil.
- **Kapan harus menggunakan Context7 MCP:**
  - Saat membuat migration, model, factory, seeder (cek syntax Laravel terbaru).
  - Saat membuat FormRequest, API Resource, atau Controller (cek method/fitur terbaru).
  - Saat menulis test dengan PHPUnit (cek attribute/assertion terbaru).
  - Saat tidak yakin dengan syntax atau fitur framework — **selalu cek dulu, jangan tebak**.
- **Jangan gunakan** Context7 MCP untuk: refactoring, debugging logic bisnis, code review, atau konsep programming umum.
