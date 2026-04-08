<?php

namespace Database\Seeders;

use App\Models\Website;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    public function run(): void
    {
        Website::create([
            'client'           => 'PT Maju Jaya',
            'pic'              => 'Budi Santoso',
            'website'          => 'Maju Jaya Corp',
            'url'              => 'majujaya.com',
            'type'             => 'Profile',
            'technology'       => 'WordPress',
            'status'           => 'Aktif',
            'internal_pic'     => 'Iqbal',
            'service_package'  => 'Pro',
            'created_year'     => '2023-01-15',
            'note'             => 'Klien lama',
            'phone'            => '08123456789',
            'email'            => 'budi@maju.com',
            'domain_provider'  => 'Niagahoster',
            'domain_email'     => 'admin@maju.com',
            'domain_reg_date'  => '2023-01-15',
            'domain_exp_date'  => '2024-01-15',
            'domain_price'     => 150000,
            'hosting_type'     => 'Dedicated Server',
            'hosting_provider' => 'DigitalOcean',
            'storage'          => 20,
            'ip_server'        => '192.168.1.1',
            'location'         => 'Singapore',
            'hosting_email'    => 'host@maju.com',
            'hosting_exp_date' => '2026-05-20',
            'hosting_price'    => 1200000,
            'admin_url'        => 'majujaya.com/wp-admin',
            'extra_access'     => 'cPanel',
            'password_loc'     => 'Bitwarden',
            'sell_price'       => 2500000,
            'pay_system'       => 'Tahunan',
            'pay_status'       => 'Lunas',
            'invoice_date'     => '2023-01-10',
        ]);

        Website::create([
            'client'           => 'Warung Makan Enak',
            'pic'              => 'Siti Aminah',
            'website'          => 'Waroeng Enak',
            'url'              => 'waroengenak.id',
            'type'             => 'Blog',
            'technology'       => 'Laravel',
            'status'           => 'Suspend',
            'internal_pic'     => 'Iqbal',
            'service_package'  => 'Basic',
            'created_year'     => '2022-11-20',
            'note'             => 'Menunggu pembayaran',
            'phone'            => '08567891234',
            'email'            => 'siti@enak.id',
            'domain_provider'  => 'Domainesia',
            'domain_email'     => 'siti@gmail.com',
            'domain_reg_date'  => '2022-11-20',
            'domain_exp_date'  => '2026-04-25',
            'domain_price'     => 250000,
            'hosting_type'     => 'Redirect',
            'hosting_provider' => 'Hostinger',
            'storage'          => 5,
            'ip_server'        => '103.11.22.33',
            'location'         => 'Jakarta',
            'hosting_email'    => 'siti@gmail.com',
            'hosting_exp_date' => '2026-05-01',
            'hosting_price'    => 500000,
            'admin_url'        => 'waroengenak.id/admin',
            'extra_access'     => 'FTP',
            'password_loc'     => 'Keepass',
            'sell_price'       => 1200000,
            'pay_system'       => 'Tahunan',
            'pay_status'       => 'Belum',
            'invoice_date'     => '2023-11-15',
        ]);
    }
}
