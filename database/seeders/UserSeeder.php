<?php

namespace Database\Seeders;

use App\Enums\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => '開発者',
            'email' => 'developper@example.com',
            'role' => Role::developper,
            'password' => bcrypt('password'),
        ]);
    }
}
