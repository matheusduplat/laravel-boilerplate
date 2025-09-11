<?php

namespace Database\Factories;

use App\Domains\DigitalWallet\Model\DigitalWallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DigitalWalletFactory extends Factory
{
    protected $model = DigitalWallet::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan' => [
                'name' => fake()->name(),
                'code' => fake()->uuid(),
            ],
            'number' => fake()->creditCardNumber(),
            'date_issue' => fake()->date('Y-m-d'),
            'validity' => fake()->optional()->dateTimeBetween('+1 month', '+2 months')?->format('Y-m-d'),
        ];
    }
}
