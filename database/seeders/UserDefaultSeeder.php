<?php

namespace Database\Seeders;

use App\Domains\Customer\Enums\CustomerStatus;
use App\Domains\Customer\Model\Customer;
use App\Domains\Employee\Enums\EmployeeStatus;
use App\Domains\Employee\Model\Employee;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\Role\Model\Role;
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
        /**
         * Created Employee
         * 
         */
        $user = User::updateOrCreate(
            [
                'email' => 'admin@email.com.br',
            ],
            [
                'name' => 'Administrador',
                'password' => 'admin',
                'email_verified_at' => now(),
                'secure_login_email' => false

            ]
        );
        $roles = Role::query()->whereIn('name', ['Administrador', 'Customer'])->get()->pluck('id')->toArray();

        $user->auditAttach('roles', $roles);

        Employee::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'name' => "Administrador",
                "status" => EmployeeStatus::ACTIVE
            ]
        );

        /**
         * Created Customer
         * 
         */
        $customer = Customer::updateOrCreate(
            [
                'email' => 'customer@email.com.br',
            ],
            [
                'name' => fake()->name(),
                'password' => 'customer',
                'email_verified_at' => now(),
                'secure_login_email' => false,
                "cpf" => "123.456.789-00",
                "birth_date" => "2000-01-01",
                'status' => CustomerStatus::ACTIVE
            ]
        );
        $roles = Role::query()->where('name', RoleDefaults::CLIENT)->get()->pluck('id')->toArray();
        $customer->auditAttach('roles', $roles);
    }
}
