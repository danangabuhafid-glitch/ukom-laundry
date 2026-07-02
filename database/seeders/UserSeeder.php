<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id_level' => 1,
            'name' => 'Admin Utama',
            'username' => 'admin',
            'email' => 'admin@laundry.com',
            'password' => 'password'
        ]);
        User::create([
            'id_level' => 2,
            'name' => 'Operator 1',
            'username' => 'operator',
            'email' => 'operator@laundry.com',
            'password' => 'password'
        ]);
        User::create([
            'id_level' => 3,
            'name' => 'Pimpinan 1',
            'username' => 'pimpinan',
            'email' => 'pimpinan@laundry.com',
            'password' => 'password'
        ]);
    }
}
