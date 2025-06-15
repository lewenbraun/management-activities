<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Participant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'website_link' => $this->faker->optional()->url(),
            'logo_path' => $this->faker->optional()->imageUrl(128, 128, 'logo', true),
            'coordinates' => json_encode([
                ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
                ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
                ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
            ]),
        ];
    }
}
