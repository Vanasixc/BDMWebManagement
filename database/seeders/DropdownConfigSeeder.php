<?php

namespace Database\Seeders;

use App\Models\DropdownConfig;
use Illuminate\Database\Seeder;

class DropdownConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            // Page: master
            ['page' => 'master', 'key' => 'type',        'label' => 'Jenis Website',    'options' => ['Berita', 'Profile', 'Blog', 'Organisasi', 'Sekolah']],
            ['page' => 'master', 'key' => 'technology',   'label' => 'CMS/Teknologi',    'options' => ['WordPress', 'Laravel', 'CodeIgniter', 'React', 'Vue']],
            ['page' => 'master', 'key' => 'status',       'label' => 'Status',           'options' => ['Aktif', 'InActive', 'Suspend']],
            ['page' => 'master', 'key' => 'internalPic',  'label' => 'PIC Internal',     'options' => ['Iqbal']],

            // Page: hosting
            ['page' => 'hosting', 'key' => 'hostingType', 'label' => 'Jenis Hosting',   'options' => ['Dedicated Server', 'Redirect', 'Shared']],

            // Page: finansial
            ['page' => 'finansial', 'key' => 'paySystem', 'label' => 'Sistem Pembayaran', 'options' => ['Tahunan', 'Bulanan']],
            ['page' => 'finansial', 'key' => 'payStatus',  'label' => 'Status Pembayaran', 'options' => ['Lunas', 'Belum']],

            // Page: reminder
            ['page' => 'reminder', 'key' => 'reminderStatus', 'label' => 'Status Reminder', 'options' => ['Sudah', 'Belum']],
        ];

        foreach ($configs as $config) {
            DropdownConfig::create($config);
        }
    }
}
