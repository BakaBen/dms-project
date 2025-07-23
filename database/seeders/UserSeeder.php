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
        $admin = 
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
        ]);
        $admin->assignRole('admin');

        $user = 
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => bcrypt('user123'),
        ]);
        $user->assignRole('user');

        $author = 
        User::factory()->create([
            'name' => 'author',
            'email' => 'author@author.com',
            'password' => bcrypt('author123'),
        ]);
        $author->assignRole('author');
    }
}
