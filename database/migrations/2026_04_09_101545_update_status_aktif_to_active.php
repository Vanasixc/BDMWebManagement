<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('websites')
            ->where('status', 'Aktif')
            ->update(['status' => 'Active']);
    }

    public function down(): void
    {
        DB::table('websites')
            ->where('status', 'Active')
            ->update(['status' => 'Aktif']);
    }
};
