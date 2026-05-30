# ToDoBackend.md
> Catatan untuk tim backend — daftar perbaikan yang berhubungan dengan server-side logic, database, dan API.
> Dibuat dalam konteks perbaikan branch `Aad`.

---

## 1. [FINANSIAL] Margin Bulanan — Accessor Model

**File:** `app/Models/Website.php`

**Konteks:**
Saat ini `getMarginAttribute()` selalu menghitung margin tahunan:
```php
public function getMarginAttribute(): int
{
    return (int) ($this->sell_price - ($this->domain_price + $this->hosting_price));
}
```

**Yang perlu dilakukan:**
- Di frontend (branch `Aad`), margin sudah ditampilkan dengan pembagian `/12` secara **display-only** di Blade ketika `pay_system = 'Bulanan'`.
- Namun, kalau ke depannya ingin margin yang tersimpan/diekspor ke CSV juga mencerminkan nilai bulanan, maka perlu dipertimbangkan untuk:
  - Menambahkan accessor baru `getMarginPerBulanAttribute()` yang mengembalikan `round(margin / 12)` jika `pay_system === 'Bulanan'`.
  - Atau mengubah logic export CSV di `exportFinansial()` agar margin yang ditulis ke CSV juga menyesuaikan `pay_system`.

**Catatan:**
Untuk sementara, nilai `total_margin` di kartu statistik (dashboard finansial) tetap menampilkan total tahunan. Jika ingin konsisten, bisa dihitung ulang di `buildStatsData()` pada `WebsiteController.php`.

---

## 2. [HOSTING] Kolom `domain_provider` di Tabel Hosting

**Konteks:**
Kolom `domain_provider` sudah ada di tabel `websites` (sudah ada sejak schema awal). Kolom ini sekarang ditampilkan di halaman Hosting dengan label **"Jasa Domain"**.

**Status:** Tidak ada migrasi yang diperlukan — kolom sudah tersedia.

**Yang perlu dicek:**
- Pastikan kolom `domain_provider` sudah masuk ke `$fillable` di `Website.php`. ✅ (Sudah ada)
- Pastikan data domain_provider bisa diinput dari form modal hosting. Saat ini form hosting (`formBuilder.js`, case `hosting`) tidak memiliki field `domain_provider`. Jika ingin bisa diedit dari halaman Hosting (bukan hanya dari Domain), perlu tambahkan field tersebut ke `case "hosting"` di `formBuilder.js`.

---

## 3. [REMINDER] Clear/Reset Field `note`

**File:** `app/Http/Controllers/WebsiteController.php` — method `clear()`

**Yang sudah dilakukan di frontend:**
- Ditambahkan case `reminder` ke `$sectionFields` di method `clear()`:
```php
'reminder' => [
    'note' => null,
],
```

**Yang perlu dicek oleh backend:**
- Pastikan validasi di `validationRules('reminder')` sudah mencakup field `note` (sudah ada: `'note' => 'nullable|string'`).
- Pastikan middleware/policy tidak memblokir akses ke endpoint `PATCH /websites/{id}/clear` dengan `section=reminder`.

---

## 4. [REMINDER] Field `note` di Fillable

**File:** `app/Models/Website.php`

**Pastikan:** Field `note` ada di array `$fillable` (kemungkinan sudah ada karena digunakan di section `akses`). Cek dan konfirmasi:
```php
protected $fillable = [
    ...
    // Akses
    'admin_url', 'admin_username', 'extra_access', 'password_loc',
    // note dipakai di akses & reminder
    ...
];
```
Jika `note` belum ada di `$fillable`, tambahkan agar update reminder bisa tersimpan.

---

## 5. [HOSTING] Validasi URL di Kolom `url` (Domain/Link)

**File:** `app/Http/Controllers/WebsiteController.php` — method `validationRules()`

**Konteks:**
Di halaman hosting sekarang, kolom pertama adalah `url` (Domain/Link website). Field `url` pada validasi section `hosting` belum ada (hanya ada di `domain` dan `master`).

**Yang perlu dilakukan:**
Jika ingin field `url` bisa diedit dari form modal hosting, tambahkan rule:
```php
'hosting' => [
    'url' => 'nullable|string|max:200',
    ...
],
```
Namun perhatikan bahwa `url` adalah data dari section `master/domain`, jadi lebih baik field ini hanya ditampilkan sebagai info (read-only) di halaman hosting, bukan sebagai field yang bisa diedit. Ini keputusan desain yang perlu didiskusikan dengan tim.

---

## Ringkasan Priority

| No | Item | Prioritas | File |
|----|------|-----------|------|
| 1 | Margin bulanan di CSV export | Medium | `WebsiteController.php` |
| 2 | Field `domain_provider` di form hosting | Low | `formBuilder.js` |
| 3 | Cek policy clear reminder | High | `WebsiteController.php` |
| 4 | Cek `note` di `$fillable` | High | `Website.php` |
| 5 | Validasi `url` di section hosting | Low | `WebsiteController.php` |
