<?php

namespace Database\Factories;

use App\Domains\Copaticipation\Model\Copaticipation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CopaticipationFactory extends Factory
{
    protected $model = Copaticipation::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'base_year' => fake()->numberBetween(2020, 2025),
            'base_month' => fake()->numberBetween(1, 12),
        ];
    }
}
