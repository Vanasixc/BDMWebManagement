<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Konversi kolom created_year dari YEAR (MySQL) ke DATE.
     * Di SQLite: kolom YEAR tidak ada, kolom DATE sudah dipakai sejak awal.
     * Migration ini hanya melakukan konversi data historis bila kolom ada.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            // MySQL: Konversi YEAR -> DATE via kolom sementara
            DB::statement("ALTER TABLE websites ADD COLUMN created_year_tmp DATE NULL");
            DB::statement("
                UPDATE websites
                SET created_year_tmp = CONCAT(created_year, '-01-01')
                WHERE created_year IS NOT NULL
            ");
            DB::statement("ALTER TABLE websites DROP COLUMN created_year");
            DB::statement("ALTER TABLE websites RENAME COLUMN created_year_tmp TO created_year");
        }
        // SQLite: Kolom created_year sudah bertipe DATE sejak migration pertama,
        // tidak ada konversi yang diperlukan.
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE websites ADD COLUMN created_year_tmp YEAR NULL");
            DB::statement("
                UPDATE websites
                SET created_year_tmp = YEAR(created_year)
                WHERE created_year IS NOT NULL
            ");
            DB::statement("ALTER TABLE websites DROP COLUMN created_year");
            DB::statement("ALTER TABLE websites RENAME COLUMN created_year_tmp TO created_year");
        }
    }
};
