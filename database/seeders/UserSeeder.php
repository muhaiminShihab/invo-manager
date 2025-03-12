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
        User::create([
            'name' => 'Mr. Admin',
            'email' => 'admin@example.com',
            'phone' => '01234567890',
            'password' => bcrypt('p@ssword')
        ]);
    }
}
