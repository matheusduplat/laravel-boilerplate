<?php

namespace Database\Factories;

use App\Domains\IncomeReport\Model\IncomeReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class IncomeReportFactory extends Factory
{
    protected $model = IncomeReport::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'year' => fake()->numberBetween(2020, 2025),
            'notified'   => fake()->boolean(70),
        ];
    }
}
