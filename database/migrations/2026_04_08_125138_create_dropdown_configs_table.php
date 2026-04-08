<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dropdown_configs', function (Blueprint $table) {
            $table->id();
            $table->string('page');    // master | hosting | finansial | reminder
            $table->string('key');     // type | technology | status | hostingType | dll
            $table->string('label');   // Label tampilan form
            $table->json('options');   // Array opsi dalam JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropdown_configs');
    }
};
