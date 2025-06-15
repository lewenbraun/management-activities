<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ActivityType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityType>
 */
class ActivityTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word().' Type',
            'icon_path' => $this->faker->optional()->imageUrl(64, 64, 'icons', true),
            'display_order' => $this->faker->unique()->numberBetween(1, 50),
        ];
    }
}
