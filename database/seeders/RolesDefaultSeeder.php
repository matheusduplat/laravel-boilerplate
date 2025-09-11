<?php

namespace Database\Seeders;

use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\Role\Model\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate([
            'name' => RoleDefaults::ADMIN,
        ]);
        Role::updateOrCreate([
            'name' => RoleDefaults::CLIENT,
            'guard_name' => 'customer',
        ]);
    }
}
