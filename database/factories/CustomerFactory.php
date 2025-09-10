<?php

namespace Database\Factories;

use App\Domains\Customer\Enums\CustomerStatus;
use App\Domains\Customer\Model\Customer;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\Role\Model\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Pest\ArchPresets\Custom;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'name_social' => fake()->name(),
            'cpf' => fake()->cpf(),
            'birth_date' => fake()->date('Y-m-d'),
            'status' => CustomerStatus::ACTIVE,
        ];
    }
}
