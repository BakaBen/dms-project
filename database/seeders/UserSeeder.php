<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = 
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin',
            'password' => bcrypt('superadmin123'),
        ]);
        $superAdmin->assignRole('superadmin');

        $admin=
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin',
            'password' => bcrypt('admin123'),
        ]);
        $admin->assignRole('admin');
    }
}
