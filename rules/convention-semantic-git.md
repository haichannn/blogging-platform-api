# Aturan Konvensi Git: Semantic Commit & Branching

Dokumen ini adalah panduan wajib dalam menggunakan Git di project ini. Tujuannya agar riwayat perubahan (*commit history*) rapi, mudah dilacak, dan mempermudah kerja sama tim. Programmer junior dan model AI **wajib** mengikuti aturan ini saat membuat *branch* baru atau melakukan *commit*.

## 🚨 Peringatan Penting Sebelum Coding
**DILARANG KERAS** melakukan proses coding (development) dan *commit* langsung di *branch* `main` atau `master`.
Setiap kali kamu akan membuat fitur baru atau memperbaiki *bug*, kamu **wajib** membuat dan berpindah ke *branch* baru terlebih dahulu.

---

## 1. Semantic Branching

Penamaan *branch* (cabang git) saat akan menambahkan fitur atau melakukan perbaikan (*hotfix*) harus terstruktur. (Referensi: [Gist Semantic Branching](https://gist.github.com/seunggabi/87f8c722d35cd07deb3f649d45a31082)).

Format dasar penamaan branch:
`<type>/<kebab-case-description>`

**Tipe Branch yang sering digunakan:**
- `feat/`: Untuk pengerjaan fitur baru.
- `fix/` atau `hotfix/`: Untuk perbaikan *bug*.
- `docs/`: Untuk penulisan dokumentasi.
- `refactor/`: Untuk perombakan kode.

❌ **CONTOH BURUK (Jangan Gunakan Ini)**
```bash
git checkout -b fitur-baru
git checkout -b ahmad-branch
git checkout -b perbaikan-login
# Aturan salah: menggunakan titik dua atau spasi di nama branch
git checkout -b "fix: repair code" 
```
*Mengapa ini buruk?* Nama *branch* seperti `ahmad-branch` tidak memberi tahu tim apa yang sedang kamu kerjakan. Spasi atau karakter aneh juga bisa merusak sistem Git. Dan yang paling buruk adalah tidak membuat *branch* sama sekali (langsung _coding_ di `main`).

✅ **CONTOH BAIK (Gunakan Ini)**
```bash
# Pastikan kamu ada di main terbaru sebelum bikin branch
git checkout main
git pull

# Bikin branch baru untuk fitur atau perbaikan
git checkout -b feat/add-feature-xxx
git checkout -b fix/repair-login-code
git checkout -b hotfix/crash-on-checkout
```
*Mengapa ini baik?* Penamaan ini memisahkan tujuan *branch* dengan tanda garis miring `/`. Sangat mudah untuk disaring (*filter*) oleh DevOps ketika ingin melihat *branch* mana saja yang berisi fitur baru dan mana yang berisi perbaikan *bug*.

---

## 2. Semantic Commit

Kita menggunakan standar **Conventional Commits** (Refernsi: [conventionalcommits.org/en/v1.0.0/](https://www.conventionalcommits.org/en/v1.0.0/)). 

Format dasar commit message:
`<type>[optional scope]: <description>`

**Tipe (Type) yang diizinkan:**
- `feat`: Menambahkan fitur baru.
- `fix`: Memperbaiki *bug* atau *error*.
- `docs`: Hanya mengubah dokumentasi (contoh: README).
- `style`: Mengubah gaya penulisan kode (spasi, format, titik koma) tanpa mengubah logika.
- `refactor`: Mengubah struktur kode tapi tidak menambah fitur atau memperbaiki *bug*.
- `chore`: Update alat bantu (build process, package manager, dsb).

❌ **CONTOH BURUK (Jangan Gunakan Ini)**
```text
update kodingan
benerin error login
tambah fitur
```
*Mengapa ini buruk?* Pesan ini sangat tidak jelas. Jika terjadi *error* di masa depan, tim akan kesulitan mencari *commit* mana yang menyebabkan masalah. Selain itu, pesan yang tidak beraturan membuat kita tidak bisa membuat *Release Notes / Changelog* secara otomatis.

✅ **CONTOH BAIK (Gunakan Ini)**
```text
feat: add feature one
feat(login): add message response after login success
fix: resolve null pointer exception in user profile
docs: update API documentation for authentication
```
*Mengapa ini baik?* Sangat spesifik. Orang yang membaca langsung tahu jenis perubahannya (`feat` atau `fix`), di mana perubahannya (`login`), dan apa yang diubah.

---
**Kesimpulan untuk AI & Junior Programmer:**
Jangan pernah _coding_ di branch `main`/`master`. Jangan pernah menjalankan perintah `git commit -m "..."` atau `git checkout -b ...` secara asal-asalan. Selalu pikirkan **tipe** perubahannya terlebih dahulu (`feat`, `fix`, `chore`, dll), lalu buat deskripsi menggunakan bahasa Inggris yang singkat, jelas, dan huruf kecil (lowercase).
