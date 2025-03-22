<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Hapus semua data di tabel users
          DB::table('users')->truncate();

          // Buat admin user
          User::create([
              'name' => 'Admin',
              'email' => 'admin@gmail.com',
              'password' => Hash::make('12345678'), // Ganti dengan password yang aman
              'role' => 'admin', // Set role sebagai admin
          ]);
  
          // Buat regular user
          User::create([
              'name' => 'test',
              'email' => 'test@gmail.com',
              'password' => Hash::make('12345678'), // Ganti dengan password yang aman
              'role' => 'user', // Set role sebagai user
          ]);
    }
}
