<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // firstOrCreate agar aman dijalankan berulang (misal: Railway restart)
        User::firstOrCreate(
            ['name' => 'superAdmin'],
            [
                'display_name' => 'Super Admin',
                'email'        => 'superadmin@wh-manager.local',
                'password'     => Hash::make('superAdmin'),
                'role'         => 'superAdmin',
                'avatar'       => 'https://ui-avatars.com/api/?name=Super+Admin&background=3B82F6&color=fff',
            ]
        );

        User::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Iqbal',
                'email'        => 'admin@wh-manager.local',
                'password'     => Hash::make('admin'),
                'role'         => 'admin',
                'avatar'       => 'https://ui-avatars.com/api/?name=Iqbal&background=10B981&color=fff',
            ]
        );
    }
}
