<?php

namespace Database\Factories;

use App\Domains\AccreditedNetworks\Enums\StatusAccreditedNetworks;
use App\Domains\AccreditedNetworks\Enums\TypeServiceAccreditedNetworks;
use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AccreditedNetWorksFactory extends Factory
{
    protected $model = AccreditedNetworks::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->word(),
            "type_service" => fake()->randomElement(TypeServiceAccreditedNetworks::cases()),
            "specialties_served" => fake()->words(3),
            "exams_attended" => fake()->words(3),
            "plans_attended" => fake()->words(3),
            "urgency_emergency" => fake()->boolean(),
            "status" => fake()->randomElement(StatusAccreditedNetworks::cases()),
        ];
    }
}
