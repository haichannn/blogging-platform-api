# Aturan Keamanan Database Laravel: Mencegah SQL Injection

Dokumen ini berisi panduan agar aplikasi Laravel kita aman dari serangan **SQL Injection (SQLi)**. Panduan ini dibuat khusus agar mudah dipahami oleh programmer junior maupun model AI.

**Apa itu SQL Injection?**
SQL Injection adalah celah keamanan di mana seorang *hacker* bisa menyusupkan perintah SQL berbahaya melalui input form atau URL. Jika berhasil, *hacker* bisa membaca semua data rahasia (seperti password), mengubah data, atau bahkan menghapus seluruh isi database kita.

Secara bawaan (*default*), Laravel sudah aman dari SQL Injection karena menggunakan sistem *Parameter Binding* (PDO). Namun, sistem ini **bisa tembus** jika kita menulis kode *raw query* yang salah.

## 1. Hindari Menggabungkan (Concatenate) Input ke dalam `Raw Query`

Fungsi berakhiran `Raw` di Laravel (seperti `whereRaw`, `selectRaw`, `orderByRaw`) mengizinkan kita menulis perintah SQL asli. **Jangan pernah memasukkan variabel input secara langsung ke dalam string query.**

❌ **KODE BURUK (Sangat Berbahaya & Jangan Gunakan Ini)**
```php
$email = $request->input('email');

// Hacker bisa mengisi email dengan: ' OR 1=1 --
// Akibatnya query menjadi: WHERE email = '' OR 1=1 --' (semua user akan ditarik!)
$users = User::whereRaw("email = '" . $email . "'")->get();
```
*Mengapa ini buruk?* Menggabungkan string (`. $email .`) akan mengeksekusi apa pun yang diketik oleh user sebagai perintah SQL, bukan sebagai data.

✅ **KODE BAIK (Gunakan Binding atau Fitur Bawaan Eloquent)**
```php
$email = $request->input('email');

// Cara 1: Gunakan parameter binding (tanda tanya ?)
$users = User::whereRaw("email = ?", [$email])->get();

// Cara 2 (Terbaik & Paling Efisien): Gunakan fitur bawaan Laravel
$users = User::where('email', $email)->get();
```
*Mengapa ini baik?* Laravel akan memastikan bahwa input `$email` benar-benar dianggap sebagai teks biasa, bukan sebagai perintah SQL. Cara 2 juga sejalan dengan kemudahan baca dan efisiensi memori (silakan baca juga `performance-rule.md`).

## 2. Penggunaan `DB::select` atau `DB::statement` yang Aman

Terkadang kita butuh menjalankan *query* SQL yang kompleks menggunakan `DB::select()`.

❌ **KODE BURUK**
```php
$id = $request->input('id');
// Jangan masukkan variabel $id ke dalam string SQL
$data = DB::select("SELECT * FROM transactions WHERE user_id = $id");
```

✅ **KODE BAIK**
```php
$id = $request->input('id');
// Gunakan parameter binding [ $id ]
$data = DB::select("SELECT * FROM transactions WHERE user_id = ?", [$id]);
```

## 3. Bahaya pada Pengurutan Kolom Dinamis (`orderBy`)

Terkadang kita ingin user bisa mengurutkan data dengan mengirim parameter di URL, misalnya `?sort=name`.

❌ **KODE BURUK**
```php
$kolom = $request->input('sort'); 
// Jika hacker mengisi sort dengan "id; DROP TABLE users;", database bisa hancur.
$users = User::orderByRaw($kolom)->get();
```

✅ **KODE BAIK (Gunakan Validasi / Whitelist)**
```php
$kolom = $request->input('sort');

// 1. Tentukan kolom apa saja yang diizinkan (Whitelist)
$allowedColumns = ['name', 'created_at', 'email'];

// 2. Cek apakah input dari user ada di dalam daftar yang diizinkan
if (in_array($kolom, $allowedColumns)) {
    // 3. Gunakan orderBy bawaan Laravel, bukan orderByRaw
    $users = User::orderBy($kolom, 'asc')->get();
} else {
    // Beri nilai default jika input tidak valid
    $users = User::orderBy('id', 'asc')->get();
}
```
*Mengapa ini baik?* User hanya bisa memilih nama kolom yang sudah kita tentukan. Input asing atau perintah SQL berbahaya dari *hacker* akan diabaikan.

---
**Kesimpulan untuk AI & Junior Programmer:**
1. **Jangan pernah percaya input user** (dari `$request`, `$_GET`, `$_POST`, dll).
2. Jika terpaksa menggunakan `DB::raw()`, `whereRaw()`, atau `DB::select()`, **selalu gunakan parameter binding `?`**.
3. Sedapat mungkin, **gunakan fungsi *Query Builder* bawaan Laravel** seperti `where()`, `select()`, dan `orderBy()`. Ini tidak hanya lebih **Aman** dari SQL Injection, tetapi juga kodenya lebih **Efisien** (sejalan dengan `performance-rule.md`).
