<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah tipe kolom created_year dari YEAR ke DATE.
     * Kolom YEAR hanya menerima nilai 4 digit angka (misal: 2026),
     * sedangkan input HTML type="date" mengirim format YYYY-MM-DD.
     */
    public function up(): void
    {
        // Konversi data lama: nilai YEAR (misal: 2026) → DATE (2026-01-01)
        DB::statement("
            ALTER TABLE websites
            MODIFY COLUMN created_year DATE NULL
        ");
    }

    public function down(): void
    {
        // Rollback: DATE → YEAR (ambil hanya tahunnya)
        DB::statement("
            ALTER TABLE websites
            MODIFY COLUMN created_year YEAR NULL
        ");
    }
};
