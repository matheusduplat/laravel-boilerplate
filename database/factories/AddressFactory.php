<?php

namespace Database\Factories;

use App\Domains\Address\Model\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'zip_code' => fake()->postcode(),
            'state' => fake()->state(),
            'city' => fake()->city(),
            'neighborhood' => fake()->streetName(),
            'street' => fake()->streetAddress(),
            'number' => fake()->buildingNumber(),
            'complement' => fake()->secondaryAddress(),
        ];
    }
}
