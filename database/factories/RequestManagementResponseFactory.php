<?php

namespace Database\Factories;

use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RequestManagementResponseFactory extends Factory
{
    protected $model = RequestManagementResponse::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'hidden' => fake()->boolean(10),
            'answer' => fake()->sentence(),
            'date_hour' => now()
        ];
    }
}
