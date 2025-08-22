<?php

namespace Database\Factories;

use App\Domains\Phone\Enums\PhoneType;
use App\Domains\Phone\Model\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class PhoneFactory extends Factory
{
    protected $model = Phone::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->phoneNumber(),
            'type' => fake()->randomElement(PhoneType::cases()),
            'whatsapp' => fake()->boolean(),
        ];
    }
}
