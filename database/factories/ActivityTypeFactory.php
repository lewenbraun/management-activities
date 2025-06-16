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
            'icon_path' => $this->faker->randomElement([
                'heroicon-o-arrow-long-left',
                'heroicon-o-arrow-path',
                'heroicon-o-bookmark',
                'heroicon-o-calendar',
                'heroicon-o-chart-bar',
                'heroicon-o-chat-bubble-left-right',
                'heroicon-o-check-circle',
                'heroicon-o-cloud',
                'heroicon-o-cog',
                'heroicon-o-document',
                'heroicon-o-envelope',
                'heroicon-o-exclamation-triangle',
                'heroicon-o-eye',
                'heroicon-o-folder',
                'heroicon-o-heart',
                'heroicon-o-tag',
                'heroicon-o-trash',
                'heroicon-o-user',
                'heroicon-o-users',
                'heroicon-o-wallet',
                'heroicon-o-wrench-screwdriver',
            ]),
            'display_order' => $this->faker->unique()->numberBetween(1, 50),
        ];
    }
}
