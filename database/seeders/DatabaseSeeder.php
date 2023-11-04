<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'login' => 'd1n0',
            'name' => 'Grzegorz',
            'surname' => 'kubok',
            'phone' => '123456789',
            'email' => 'gigog456@gmail.com',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'password' => Hash::make('gigog890'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'login' => 'pow',
            'name' => 'pow',
            'surname' => '',
            'phone' => '123456789',
            'email' => 'pow@pow.pl',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'password' => Hash::make('gigog890'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
