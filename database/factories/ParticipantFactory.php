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
            'coordinates' => [
                ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
                ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
                ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
            ],
        ];
    }

    public function configure(): self
    {
        $dir = __DIR__.'/fixtures/logos';

        $files = array_merge(
            glob("$dir/*.png") ?: []
        );

        return $this->afterCreating(function (Participant $participant) use ($files): void {
            $path = $files[array_rand($files)];

            $participant
                ->addMedia($path)
                ->preservingOriginal()
                ->toMediaCollection('logos');
        });
    }
}
