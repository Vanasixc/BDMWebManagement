<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        DB::statement("ALTER TABLE websites ADD COLUMN created_year_tmp DATE NULL");

        DB::statement("
            UPDATE websites
            SET created_year_tmp = CONCAT(created_year, '-01-01')
            WHERE created_year IS NOT NULL
        ");

        DB::statement("ALTER TABLE websites DROP COLUMN created_year");

        DB::statement("ALTER TABLE websites RENAME COLUMN created_year_tmp TO created_year");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE websites ADD COLUMN created_year_tmp YEAR NULL");

        DB::statement("
            UPDATE websites
            SET created_year_tmp = YEAR(created_year)
            WHERE created_year IS NOT NULL
        ");

        DB::statement("ALTER TABLE websites DROP COLUMN created_year");

        DB::statement("ALTER TABLE websites RENAME COLUMN created_year_tmp TO created_year");
    }
};

