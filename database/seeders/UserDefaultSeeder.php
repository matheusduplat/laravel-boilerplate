<?php

namespace Database\Seeders;

use App\Domains\User\Model\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@email.com.br',
            ],
            [
                'name' => 'Administrador',
                'password' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
