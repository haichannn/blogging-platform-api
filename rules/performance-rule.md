# Aturan Performa Database Laravel

Dokumen ini berisi panduan untuk menjaga agar aplikasi Laravel kita tetap cepat saat mengambil data dari database. Panduan ini dibuat agar mudah dipahami oleh programmer junior maupun model AI.

## 1. Hindari Masalah N+1 (N+1 Query Problem)

**Apa itu N+1?**
Masalah ini terjadi ketika kode kita melakukan *query* ke database di dalam sebuah *loop* (perulangan). Jika ada 100 data, maka akan ada 1 tambahan *query* utama, dan 100 *query* di dalam *loop*. Totalnya 101 *query*! Ini akan membuat server sangat lambat.

❌ **KODE BURUK (Jangan gunakan ini)**
```php
// Mengambil semua buku (1 query)
$books = Book::all();

foreach ($books as $book) {
    // Di dalam loop, kita memanggil relasi 'author'
    // Ini akan menjalankan 1 query BARU untuk setiap buku!
    echo $book->author->name;
}
```
*Mengapa ini buruk?* Jika ada 1000 buku, kode di atas akan menembak database sebanyak 1001 kali. Koneksi ke database memakan waktu dan akan membuat respon aplikasi sangat lambat.

✅ **KODE BAIK (Gunakan ini)**
```php
// Gunakan Eager Loading dengan method 'with()'
// Ini hanya akan menjalankan 2 query secara total, berapapun jumlah bukunya.
$books = Book::with('author')->get();

foreach ($books as $book) {
    echo $book->author->name; // Sudah tidak ada query ke database di sini
}
```
*Mengapa ini baik?* Laravel hanya melakukan 2 *query*:
1. Mengambil semua buku.
2. Mengambil semua *author* yang terkait dengan buku-buku tersebut sekaligus dengan *query* `IN (...)`.

## 2. Ambil Kolom yang Dibutuhkan Saja (Gunakan Select)

❌ **KODE BURUK**
```php
// Mengambil semua kolom dari tabel users (id, name, email, password, bio_panjang, dll)
$users = User::all();

foreach ($users as $user) {
    echo $user->name;
}
```
*Mengapa ini buruk?* Jika kamu hanya butuh nama, mengambil data `password` atau teks `bio` yang panjang akan menghabiskan memori server (RAM) dan memperlambat transfer data dari database ke aplikasi.

✅ **KODE BAIK**
```php
// Hanya ambil kolom id dan name
$users = User::select('id', 'name')->get();

foreach ($users as $user) {
    echo $user->name;
}
```

## 3. Jangan Memuat Semua Data ke Memori Sekaligus (Gunakan Chunk/Cursor)

Jika kamu ingin memproses ribuan atau jutaan baris data (misalnya untuk membuat laporan atau mengekspor CSV):

❌ **KODE BURUK**
```php
// Jika tabel users ada 1 juta baris, ini akan membuat RAM penuh (Out of Memory) dan aplikasi crash!
$users = User::all();

foreach ($users as $user) {
    // proses data
}
```

✅ **KODE BAIK**
```php
// Memproses data per 100 baris
User::chunk(100, function ($users) {
    foreach ($users as $user) {
        // proses data
    }
});
```
*Mengapa ini baik?* Aplikasi hanya mengambil 100 data ke memori, memprosesnya, menghapusnya dari memori, lalu mengambil 100 data berikutnya. Aplikasi tidak akan pernah kehabisan RAM.

## 4. Menghitung Jumlah Data (Count)

❌ **KODE BURUK**
```php
$jumlahUser = User::get()->count();
```
*Mengapa ini buruk?* Kode di atas akan mengambil *semua* data user dari database ke dalam PHP/RAM, lalu PHP baru menghitung jumlahnya.

✅ **KODE BAIK**
```php
$jumlahUser = User::count();
```
*Mengapa ini baik?* Kode ini menyuruh database langsung menghitung datanya menggunakan perintah SQL `SELECT COUNT(*)`. Database melakukan ini dengan sangat cepat tanpa mengirim semua isinya ke PHP.

---
**Kesimpulan untuk AI & Junior:** 
Setiap kali kamu menulis kode yang mengambil data menggunakan *Eloquent* atau *Query Builder*, tanyakan pada dirimu:
1. *Apakah kode ini akan memanggil relasi di dalam loop?* (Jika ya, gunakan `with`).
2. *Apakah saya memuat terlalu banyak data/kolom yang tidak saya pakai?* (Jika ya, gunakan `select` atau batasi dengan `limit`/`chunk`).
