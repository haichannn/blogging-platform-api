# Aturan Standar Response API Laravel

Dokumen ini adalah panduan wajib untuk memastikan semua _response_ API yang kita buat memiliki struktur yang **seragam (konsisten)**. Aturan ini sangat penting untuk dibaca oleh programmer junior dan model AI agar *frontend developer* (Web/Mobile) tidak kebingungan saat membaca data dari API kita.

## Mengapa Response Harus Konsisten?
Jika setiap fungsi API mengembalikan bentuk data yang berbeda-beda (misal kadang dibungkus array, kadang object langsung, atau *error message*-nya berbeda bentuk), maka aplikasi *frontend* akan sangat rawan *error* (crash). Selalu patuhi standar format di bawah ini.

---

## 1. Response Sukses (HTTP Status: 2xx)

Jika permintaan API berhasil, **langsung kembalikan datanya** tanpa perlu membungkusnya lagi ke dalam *wrapper* seperti `{"status": true, "data": { ... }}` (kecuali untuk paginasi bawaan Laravel).

**Bentuk JSON yang Diharapkan:**
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

❌ **KODE BURUK (Jangan Gunakan Ini)**
```php
// Buruk karena membungkus data dengan format yang tidak standar (ada 'success' dan 'data')
// Dan menggunakan status code 200 secara manual padahal bisa otomatis
return response()->json([
    'success' => true,
    'data' => $post
], 200);
```

✅ **KODE BAIK (Gunakan Ini)**
```php
// Baik: Langsung mengembalikan model/resource. Laravel otomatis mengubahnya menjadi JSON
// dan memberikan status HTTP 200 (OK) atau 201 (Created).
return response()->json($post, 200); 

// Atau jika menggunakan Eloquent API Resource (Sangat Disarankan)
return new PostResource($post);
```

**Standar HTTP Status Code untuk Sukses:**
- `200 OK` : Digunakan untuk operasi baca (GET), ubah (PUT/PATCH), atau hapus (DELETE) yang berhasil.
- `201 Created` : Digunakan spesifik saat data baru berhasil ditambahkan (POST).

---

## 2. Response Gagal / Error (HTTP Status: 4xx & 5xx)

Jika terjadi kesalahan (validasi gagal, data tidak ditemukan, dll), API **harus** mengembalikan format JSON yang mengandung _key_ `"errors"`. Di dalam `"errors"` ini harus jelas letak kesalahannya ada di mana (misal berdasarkan nama kolom/field). 

**Bentuk JSON yang Diharapkan (Contoh Validasi Gagal):**
```json
{
  "errors": {
      "title": "tidak boleh kosong"
  } 
}
```

❌ **KODE BURUK (Jangan Gunakan Ini)**
```php
// 1. Buruk karena HTTP status-nya 200 (OK), padahal ini error!
// 2. Buruk karena format key-nya menggunakan "message", bukan "errors". Frontend akan gagal membacanya.
return response()->json([
    'message' => 'Validasi gagal, title kosong'
], 200); 
```

✅ **KODE BAIK (Gunakan Ini)**
```php
// Jika menggunakan Validator bawaan Laravel secara manual:
$validator = Validator::make($request->all(), [
    'title' => 'required'
]);

if ($validator->fails()) {
    // Baik: Struktur respons konsisten dengan key "errors" dan HTTP Status 422
    return response()->json([
        'errors' => $validator->errors()
    ], 422);
}

// Catatan: Jika kamu menggunakan FormRequest Laravel ($request->validate()), 
// Laravel sudah OTOMATIS mengembalikan struktur JSON 422 ini. Jadi biarkan Laravel bekerja!
```

**Standar HTTP Status Code untuk Gagal:**
- `422 Unprocessable Entity` : Untuk input user yang tidak valid / tidak lolos validasi.
- `404 Not Found` : Untuk data yang tidak ditemukan di database (misal: mencari ID yang tidak ada).
- `401 Unauthorized` : Jika user belum login tapi mencoba mengakses API terlarang.
- `403 Forbidden` : Jika user sudah login, tapi tidak punya akses/hak cipta ke data tersebut.
- `500 Internal Server Error` : Jika kodingan/database bermasalah dari sisi server kita.

---
**Kesimpulan untuk AI & Junior Programmer:** 
Selalu gunakan *status code* yang tepat sesuai standar HTTP. Jangan pernah mengirimkan kode `200 OK` jika yang sebenarnya terjadi adalah *error*. Mengirim *error* pada status 200 adalah sebuah praktik *anti-pattern* yang akan menyusahkan tim Frontend dalam membuat penanganan gagal (error handling) di aplikasi mereka.
