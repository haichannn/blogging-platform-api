# PRD: Blogging Platform API

Dokumen ini adalah panduan lengkap (Product Requirements Document) untuk membangun REST API **Blogging Platform**. Programmer junior dan model AI **wajib** membaca seluruh isi dokumen ini serta semua file di folder `rules/` sebelum memulai implementasi.

---

## Goals

- Create a new blog post.
- Update an existing blog post.
- Delete an existing blog post.
- Get a single blog post.
- Get all blog posts.
- Filter blog posts by a search term.

---

## Tech Stack

| Komponen         | Teknologi          |
| ---------------- | ------------------ |
| Bahasa           | PHP 8.3            |
| Framework        | Laravel 13.8       |
| Database         | SQLite (bawaan)    |
| Package Manager  | Composer           |
| Testing          | PHPUnit 12.5.12    |

---

## Database Design

### Tabel `posts`

| Kolom        | Tipe              | Keterangan                                       |
| ------------ | ----------------- | ------------------------------------------------ |
| `id`         | `INTEGER` (PK, AI)| Primary key, auto increment.                     |
| `title`      | `VARCHAR(255)`     | Judul blog post. Wajib diisi.                    |
| `content`    | `TEXT`             | Isi/konten blog post. Wajib diisi.               |
| `category`   | `VARCHAR(100)`     | Kategori blog post. Wajib diisi.                 |
| `tags`       | `JSON`             | Daftar tag, disimpan sebagai JSON array.          |
| `created_at` | `TIMESTAMP`        | Otomatis diisi Laravel saat data dibuat.          |
| `updated_at` | `TIMESTAMP`        | Otomatis diisi Laravel saat data diperbarui.      |

> **Catatan:** Kolom `tags` disimpan sebagai JSON karena data tag bersifat sederhana (array of string) dan tidak memerlukan relasi tabel terpisah untuk skala project ini.

### Contoh Migration

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title', 255);
    $table->text('content');
    $table->string('category', 100);
    $table->json('tags')->nullable();
    $table->timestamps();
});
```

---

## API Endpoints

### 1. Create Blog Post

- **Method:** `POST`
- **URL:** `/posts`
- **Validasi:** Gunakan `FormRequest` (bukan validasi di controller).

**Request Body:**
```json
{
  "title": "My First Blog Post",
  "content": "This is the content of my first blog post.",
  "category": "Technology",
  "tags": ["Tech", "Programming"]
}
```

**Response Sukses** — `201 Created`:
```json
{
  "id": 1,
  "title": "My First Blog Post",
  "content": "This is the content of my first blog post.",
  "category": "Technology",
  "tags": ["Tech", "Programming"],
  "createdAt": "2021-09-01T12:00:00Z",
  "updatedAt": "2021-09-01T12:00:00Z"
}
```

**Response Gagal (Validasi)** — `422 Unprocessable Entity`:
```json
{
  "errors": {
    "title": ["The title field is required."]
  }
}
```

---

### 2. Update Blog Post

- **Method:** `PATCH`
- **URL:** `/posts/{id}`
- **Validasi:** Gunakan `FormRequest`.

**Request Body:**
```json
{
  "title": "My Updated Blog Post",
  "content": "This is the updated content of my first blog post.",
  "category": "Technology",
  "tags": ["Tech", "Programming"]
}
```

**Response Sukses** — `200 OK`:
```json
{
  "id": 1,
  "title": "My Updated Blog Post",
  "content": "This is the updated content of my first blog post.",
  "category": "Technology",
  "tags": ["Tech", "Programming"],
  "createdAt": "2021-09-01T12:00:00Z",
  "updatedAt": "2021-09-01T12:30:00Z"
}
```

**Response Gagal (Tidak Ditemukan)** — `404 Not Found`:
```json
{
  "message": "Post not found."
}
```

**Response Gagal (Validasi)** — `422 Unprocessable Entity`:
```json
{
  "errors": {
    "title": ["The title field is required."]
  }
}
```

---

### 3. Delete Blog Post

- **Method:** `DELETE`
- **URL:** `/posts/{id}`

**Response Sukses** — `204 No Content`:
Tidak mengembalikan body apapun.

**Response Gagal (Tidak Ditemukan)** — `404 Not Found`:
```json
{
  "message": "Post not found."
}
```

---

### 4. Get Single Blog Post

- **Method:** `GET`
- **URL:** `/posts/{id}`

**Response Sukses** — `200 OK`:
```json
{
  "id": 1,
  "title": "My First Blog Post",
  "content": "This is the content of my first blog post.",
  "category": "Technology",
  "tags": ["Tech", "Programming"],
  "createdAt": "2021-09-01T12:00:00Z",
  "updatedAt": "2021-09-01T12:00:00Z"
}
```

**Response Gagal (Tidak Ditemukan)** — `404 Not Found`:
```json
{
  "message": "Post not found."
}
```

---

### 5. Get All Blog Posts

- **Method:** `GET`
- **URL:** `/posts`

**Response Sukses** — `200 OK`:
```json
[
  {
    "id": 1,
    "title": "My First Blog Post",
    "content": "This is the content of my first blog post.",
    "category": "Technology",
    "tags": ["Tech", "Programming"],
    "createdAt": "2021-09-01T12:00:00Z",
    "updatedAt": "2021-09-01T12:00:00Z"
  },
  {
    "id": 2,
    "title": "Another Post",
    "content": "Content here.",
    "category": "Science",
    "tags": ["Research"],
    "createdAt": "2021-09-02T08:00:00Z",
    "updatedAt": "2021-09-02T08:00:00Z"
  }
]
```

**Response (Data Kosong)** — `200 OK`:
```json
[]
```

---

### 6. Search Blog Posts

- **Method:** `GET`
- **URL:** `/posts?search={term}`
- **Pencarian berdasarkan:** `title` dan `category`.
- **Gunakan** Eloquent `where()` dengan `LIKE` — **jangan** gunakan `whereRaw()` dengan concatenation (lihat `rules/security-sql-injection.md`).

**Contoh Request:** `GET /posts?search=Technology`

**Response Sukses** — `200 OK`:
Mengembalikan array dari post yang cocok (format sama seperti Get All Blog Posts).

**Response (Data Tidak Ditemukan)** — `200 OK`:
Mengembalikan array kosong jika tidak ada post yang cocok dengan kata kunci pencarian.
```json
[]
```

---

## Validation Rules (FormRequest)

Aturan validasi yang harus diterapkan untuk Create dan Update:

| Field      | Rules                                    |
| ---------- | ---------------------------------------- |
| `title`    | `required`, `string`, `max:255`          |
| `content`  | `required`, `string`                     |
| `category` | `required`, `string`, `max:100`          |
| `tags`     | `nullable`, `array`                      |
| `tags.*`   | `string`                                 |

---

## Response JSON Key Format

Semua response JSON key menggunakan **camelCase** (bukan snake_case):
- `createdAt` (bukan `created_at`)
- `updatedAt` (bukan `updated_at`)

Gunakan **Eloquent API Resource** untuk mentransformasi key dari snake_case (database) ke camelCase (response JSON).

---

## Catatan Penting

- Patuhi aturan response API di `rules/api-rule.md`.
- Patuhi aturan keamanan SQL di `rules/security-sql-injection.md`.
- Patuhi aturan performa database di `rules/performance-rule.md`.
- Patuhi aturan git di `rules/convention-semantic-git.md`.
- Gunakan `FormRequest` untuk validasi, bukan validasi manual di controller.
- Semua method wajib memiliki PHPDoc comment (lihat `AGENTS.md`).
- Semua test method wajib menggunakan `#[TestDox('...')]` attribute (lihat `AGENTS.md`).
