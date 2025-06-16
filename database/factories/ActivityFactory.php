<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(rand(3, 8)),
            'source' => $this->faker->optional()->url(),
            'image_path' => $this->faker->optional()->imageUrl(640, 480, 'activity', true),
            'short_description' => $this->faker->sentence(rand(10, 20)),
            'registration_link' => $this->faker->optional()->url(),
            'location_description' => $this->faker->address(),
            'coordinates' => [
                ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
                ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
                ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
                ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
            ],
            'schedule' => [
                ['date' => $this->faker->date(), 'time' => $this->faker->time()],
                ['date' => $this->faker->date(), 'time' => $this->faker->time()],
            ],
            'activity_type_id' => ActivityType::factory(),
            'creator_id' => User::factory(),
        ];
    }
}
