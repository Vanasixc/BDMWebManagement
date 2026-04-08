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

            // == Master Data ==
            $table->string('client');           // Nama Client
            $table->string('pic');              // PIC Client
            $table->string('website');          // Nama Website
            $table->string('url');              // URL Website
            $table->string('type');             // Jenis Website
            $table->string('technology');       // CMS/Teknologi
            $table->string('status')->default('Aktif'); // Aktif|InActive|Suspend
            $table->string('internal_pic');     // PIC Internal
            $table->string('service_package')->nullable();
            $table->date('created_year')->nullable();
            $table->text('note')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // == Domain ==
            $table->string('domain_provider')->nullable();
            $table->string('domain_email')->nullable();
            $table->date('domain_reg_date')->nullable();
            $table->date('domain_exp_date')->nullable();
            $table->bigInteger('domain_price')->default(0);

            // == Hosting ==
            $table->string('hosting_type')->nullable();
            $table->string('hosting_provider')->nullable();
            $table->integer('storage')->default(0);
            $table->string('ip_server')->nullable();
            $table->string('location')->nullable();
            $table->string('hosting_email')->nullable();
            $table->date('hosting_exp_date')->nullable();
            $table->bigInteger('hosting_price')->default(0);

            // == Access ==
            $table->string('admin_url')->nullable();
            $table->string('extra_access')->nullable();
            $table->string('password_loc')->nullable();

            // == Financial ==
            $table->bigInteger('sell_price')->default(0);
            $table->string('pay_system')->default('Tahunan');
            $table->string('pay_status')->default('Belum');
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
