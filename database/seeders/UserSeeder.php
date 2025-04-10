<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Utama',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'phone' => '081234567890',
                'location' => 'Yogyakarta',
                'about_me' => 'Saya adalah admin utama sistem ini.',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'admin2@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'phone' => '082112345678',
                'location' => 'Sleman',
                'about_me' => 'Saya senang dengan teknologi dan pengembangan sistem.',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Smith',
                'email' => 'admin3@example.com',
                'email_verified_at' => null,
                'password' => Hash::make('admin123'),
                'phone' => '085766543210',
                'location' => 'Bantul',
                'about_me' => 'Pengguna baru yang sedang belajar Laravel.',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
