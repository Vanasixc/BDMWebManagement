# Panduan Migrasi dari SQLite ke MySQL

Dokumen ini menjelaskan langkah lengkap untuk migrasi database dari **SQLite** (development) ke **MySQL** (production).

---

## Prasyarat

- MySQL Server 8.0+ sudah terinstall dan berjalan
- PHP extension `pdo_mysql` sudah aktif (cek via `php -m | findstr pdo_mysql`)
- Akses ke MySQL dengan user yang punya hak CREATE DATABASE

---

## Langkah 1 — Buat Database MySQL

Login ke MySQL dan buat database baru:

```sql
CREATE DATABASE wh_manager
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Buat user khusus (lebih aman daripada pakai root)
CREATE USER 'wh_user'@'localhost' IDENTIFIED BY 'password_kuat_kamu';
GRANT ALL PRIVILEGES ON wh_manager.* TO 'wh_user'@'localhost';
FLUSH PRIVILEGES;
```

---

## Langkah 2 — Update File `.env`

Buka file `.env` di root project dan ubah bagian database:

```env
# Sebelum (SQLite):
DB_CONNECTION=sqlite

# Sesudah (MySQL):
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wh_manager
DB_USERNAME=wh_user
DB_PASSWORD=password_kuat_kamu
```

> Hapus atau komentari baris `DB_CONNECTION=sqlite` yang lama.

---

## Langkah 3 — Bersihkan Cache Config

```bash
php artisan config:clear
php artisan cache:clear
```

---

## Langkah 4 — Jalankan Migrasi

```bash
# Fresh install (data akan di-reset):
php artisan migrate:fresh --seed

# Atau jika ingin preserve struktur tanpa reset data:
php artisan migrate
```

---

## Langkah 5 — Verifikasi

```bash
php artisan tinker
>>> DB::connection()->getPdo();
# Harus muncul: PDO Object (tidak error)

>>> App\Models\User::count();
# Harus muncul: 2
```

---

## ⚠️ Perbedaan SQLite vs MySQL yang Perlu Diperhatikan

| Aspek | SQLite | MySQL |
|---|---|---|
| Perbandingan string | Case-insensitive | Case-sensitive (by default) |
| JSON column | Disimpan sebagai TEXT | Native JSON type |
| `LIKE` search | Tidak case-sensitive | Case-sensitive |
| Locking | File-level | Row-level (lebih baik untuk concurrent) |

### Fix Case-Sensitivity Search (Jika Diperlukan)

Jika search tidak menemukan data karena case-sensitivity, ganti collation column atau gunakan query:

```php
// Di WebsiteController.php, ubah query search:
$query->where(function ($q) use ($search) {
    $q->whereRaw('LOWER(client) LIKE ?', ["%".strtolower($search)."%"])
      ->orWhereRaw('LOWER(website) LIKE ?', ["%".strtolower($search)."%"]);
});
```

---

## Deploy ke Server Produksi

Setelah database MySQL siap, lanjutkan setup production:

```bash
# 1. Optimasi autoload
composer install --optimize-autoloader --no-dev

# 2. Build assets production
npm run build

# 3. Cache config & routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Set permission (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Rollback ke SQLite (Jika Perlu)

Cukup ubah kembali `.env`:

```env
DB_CONNECTION=sqlite
# Hapus atau komentari semua DB_HOST, DB_PORT, dll.
```

Lalu jalankan `php artisan config:clear` dan server akan kembali menggunakan SQLite.
