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
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            
            // -- Data Klien --
            $table->string('client');
            $table->string('jenis_klien')->nullable();
            $table->string('pic')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            
            // -- Data Website Utama --
            $table->string('website');
            $table->string('url')->nullable();
            $table->string('type', 100)->nullable();
            $table->string('technology', 100)->nullable();
            $table->string('status', 50)->default('Active');
            $table->string('prioritas', 50)->default('Normal');
            $table->string('internal_pic')->nullable();
            $table->string('service_package')->nullable();
            $table->year('created_year')->nullable();
            $table->text('note')->nullable();
            
            // -- Data Domain --
            $table->string('domain_name')->nullable();
            $table->string('domain_provider')->nullable();
            $table->string('domain_email')->nullable();
            $table->date('domain_reg_date')->nullable();
            $table->date('domain_exp_date')->nullable();
            $table->integer('domain_duration')->nullable();
            $table->boolean('is_auto_renew')->default(false);
            $table->decimal('domain_price', 15, 2)->nullable();
            
            // -- Data Hosting --
            $table->string('hosting_type', 100)->nullable();
            $table->string('hosting_package')->nullable();
            $table->string('hosting_provider')->nullable();
            $table->string('storage', 100)->nullable();
            $table->string('ip_server', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('hosting_email')->nullable();
            $table->date('hosting_exp_date')->nullable();
            $table->decimal('hosting_price', 15, 2)->nullable();
            
            // -- Data Akses --
            $table->string('admin_url')->nullable();
            $table->string('admin_username')->nullable();
            $table->text('extra_access')->nullable();
            $table->string('password_loc')->nullable();
            
            // -- Data Finansial --
            $table->decimal('sell_price', 15, 2)->nullable();
            $table->string('pay_system', 50)->nullable();
            $table->string('pay_status', 50)->nullable();
            $table->date('invoice_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
