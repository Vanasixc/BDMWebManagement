# WebHouse Manager

Aplikasi manajemen infrastruktur website untuk web house / agensi digital. Dibangun dengan **Laravel 13** dan **Tailwind CSS**.

---

## ✅ Fitur Utama

| Modul | Deskripsi |
|---|---|
| **Dashboard** | Statistik website, expiring alert, grafik revenue & margin |
| **Master Table** | Data induk website client (PIC, teknologi, status) |
| **Domain** | Kelola domain: provider, harga, tanggal expired |
| **Hosting** | Kelola hosting: tipe, storage, lokasi server |
| **Akses** | URL admin, password manager, catatan akses |
| **Finansial** | Harga jual, margin, status pembayaran |
| **Reminder** | Monitoring status expiry domain & hosting |
| **Akun** | Manajemen user (khusus superAdmin) |

---

## 🛠 Tech Stack

- **Backend**: PHP 8.3 + Laravel 13
- **Database**: SQLite (development) / MySQL (production)
- **Frontend**: Blade Templates + Tailwind CSS v4
- **Charts**: Chart.js v4 (via CDN)
- **Confirmation Dialog**: SweetAlert2 v11 (via CDN)
- **Build Tool**: Vite
- **Icons**: Custom SVG inline components

---

## 🚀 Cara Menjalankan (Development)

### 1. Pindah ke direktori project
```bash
cd E:\PHP\webManajemen
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Salin file environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup database SQLite (development)
Pastikan file `.env` berisi:
```
DB_CONNECTION=sqlite
```
File database akan otomatis dibuat di `database/database.sqlite`.

```bash
php artisan migrate:fresh --seed
```

### 5. Build assets
```bash
npm run build
# atau untuk development dengan hot-reload:
npm run dev
```

### 6. Jalankan server
```bash
php artisan serve
```
Buka di browser: **http://127.0.0.1:8000**

---

## 🔑 Kredensial Default

| Role | Username | Password |
|---|---|---|
| Super Admin | `superAdmin` | `superAdmin` |
| Admin | `admin` | `admin` |

> ⚠️ **Wajib ganti password** setelah deploy ke production!

---

## 📁 Struktur Direktori Penting

```
webManajemen/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Login/logout
│   │   │   ├── DashboardController.php     # Dashboard stats & charts
│   │   │   ├── WebsiteController.php       # CRUD semua section
│   │   │   ├── DropdownConfigController.php # Manage dropdown options
│   │   │   └── AccountController.php       # User management
│   │   └── Middleware/
│   │       └── CheckRole.php              # Role-based access control
│   └── Models/
│       ├── User.php                       # User + auth model
│       ├── Website.php                    # Website data model
│       └── DropdownConfig.php             # Dynamic dropdown config
├── database/
│   ├── migrations/                        # Database schema
│   └── seeders/                           # Data default
└── resources/
    ├── css/app.css                        # Tailwind + custom styles
    ├── js/app.js                          # Dark mode, sidebar, modal, charts
    └── views/
        ├── layouts/app.blade.php          # Master layout
        ├── components/                    # Reusable Blade components
        │   ├── data-table.blade.php       # Tabel reusable
        │   ├── modal-form.blade.php       # Modal reusable
        │   ├── status-badge.blade.php     # Badge status
        │   ├── reminder-badge.blade.php   # Badge reminder
        │   └── icon.blade.php             # SVG icons
        ├── auth/login.blade.php
        ├── dashboard/index.blade.php
        ├── sections/                      # master, domain, hosting, akses, finansial, reminder
        ├── akun/index.blade.php
        └── errors/403.blade.php
```

---

## 🔄 Migrasi ke MySQL (Production)

Lihat panduan lengkap: **[MYSQL_MIGRATION.md](MYSQL_MIGRATION.md)**

Ringkasan cepat:
1. Edit `.env` — ubah `DB_CONNECTION=mysql` dan isi host/user/password
2. Buat database di MySQL: `CREATE DATABASE wh_manager CHARACTER SET utf8mb4;`
3. Jalankan: `php artisan migrate:fresh --seed`

---

## ➕ Menambah User Baru

Via halaman **Akun** (login sebagai superAdmin), atau via Artisan Tinker:
```bash
php artisan tinker
>>> App\Models\User::create(['name'=>'operator','display_name'=>'Nama Operator','email'=>'op@wh.local','password'=>bcrypt('password123'),'role'=>'admin']);
```

---

## 🌙 Dark Mode

Dark mode tersimpan di `localStorage` browser dan persisten antar sesi.

---

## 📡 API Endpoints (Internal)

| Method | URL | Keterangan |
|---|---|---|
| GET | `/websites/{id}` | Ambil data website (JSON untuk modal) |
| POST | `/dropdown/add` | Tambah opsi dropdown |
| POST | `/dropdown/remove` | Hapus opsi dropdown |

---

## 📝 Catatan untuk Developer

### Modal Form
Modal CRUD di-include **langsung di `layouts/app.blade.php`** (bukan di masing-masing section view).
Ini disengaja agar modal selalu berada di root `<body>` dan `position: fixed` berfungsi relatif terhadap viewport, bukan terkekang oleh div scroll parent.

> ⚠️ **Jangan** memindahkan `@include('components.modal-form')` ke dalam `@section('content')` di section views — modal akan terkekang oleh container scrollable dan tampil tidak di tengah.

### Tailwind CSS v4 Dark Mode
Tailwind v4 mengubah default dark mode dari class-based ke `prefers-color-scheme`. Konfigurasi `@custom-variant dark` di `app.css` diperlukan agar `dark:` classes bekerja dengan toggle class `.dark` di `<html>`.

### SweetAlert2
Semua konfirmasi delete dan input dropdown menggunakan SweetAlert2. SweetAlert2 dimuat via CDN di `layouts/app.blade.php` dan otomatis menyesuaikan dark/light mode.
