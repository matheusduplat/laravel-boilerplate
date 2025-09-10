<?php

namespace Database\Factories;

use App\Domains\Employee\Enums\EmployeeStatus;
use App\Domains\Employee\Model\Employee;
use App\Domains\User\Model\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'status' => fake()->randomElement(EmployeeStatus::cases()),
        ];
    }

    public function user()
    {
        return $this->state(function (array $item) {
            $item['user_id'] = User::factory()->administrador()->create()->id;
            return $item;
        });
    }
}
