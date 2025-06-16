<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Participant; // Добавлено: импорт модели Participant
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
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(rand(3, 8)),
            'source' => $this->faker->optional()->url(),
            'short_description' => $this->faker->sentence(rand(10, 15)),
            'registration_link' => $this->faker->optional()->url(),
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
            'participant_id' => Participant::factory(),
        ];
    }

    public function configure(): self
    {
        $dir = __DIR__.'/fixtures/activity_attachments';

        $files = array_merge(
            glob("$dir/*.png") ?: [],
            glob("$dir/*.jpg") ?: [],
            glob("$dir/*.jpeg") ?: [],
        );

        return $this->afterCreating(function (Activity $activity) use ($files): void {
            $count = rand(1, 3);

            if ($files === []) {
                return;
            }

            $chosenFiles = collect($files)->shuffle()->take(min($count, count($files)));

            foreach ($chosenFiles as $file) {
                $activity
                    ->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection('attachments');
            }
        });
    }
}
