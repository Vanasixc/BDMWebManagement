<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah status "Aktif" → "Active" di tabel websites.
     */
    public function up(): void
    {
        DB::table('websites')
            ->where('status', 'Aktif')
            ->update(['status' => 'Active']);
    }

    /**
     * Rollback: kembalikan "Active" → "Aktif".
     */
    public function down(): void
    {
        DB::table('websites')
            ->where('status', 'Active')
            ->update(['status' => 'Aktif']);
    }
};
