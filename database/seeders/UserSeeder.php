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
        if (!User::count()) {
            User::create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin123')
            ]);
            User::create([
                'name' => 'ilyas',
                'email' => 'ilyas@gmail.com',
                'password' => bcrypt('ilyas123')
            ]);
        }
    }
}
