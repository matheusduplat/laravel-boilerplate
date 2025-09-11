<?php

namespace Database\Factories;

use App\Domains\Bill\Enums\BillStatus;
use App\Domains\Bill\Model\Bill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BillFactory extends Factory
{
    protected $model = Bill::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_id'   => fake()->uuid(),
            'base_year'  => fake()->numberBetween(2020, 2025), // anos entre 2023 e 2030(),
            'base_month' => fake()->numberBetween(1, 12),
            'issue_date' => fake()->date(),
            'due_date'   => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'low_date'   => fake()->optional()->dateTimeBetween('+1 month', '+2 months')?->format('Y-m-d'),
            'value'      => fake()->randomNumber(9, true), // valores entre 100 e 5000
            'status'     => fake()->randomElement(BillStatus::cases()),
            'notified'   => fake()->boolean(70),
        ];
    }
}
