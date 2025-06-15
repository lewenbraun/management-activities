<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Activity;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ActivityType;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Создаем пользователей
        User::factory(10)->create();

        ActivityType::factory(5)->create();

        Participant::factory(8)->create();

        Activity::factory(20)->create()->each(function ($activity): void {
            $participants = Participant::inRandomOrder()->limit(rand(1, 3))->get();
            $activity->participants()->attach($participants);

            $users = User::inRandomOrder()->limit(rand(0, 5))->get(); // От 0 до 5 пользователей могут добавить в избранное
            $activity->favoritedByUsers()->attach($users);
        });
    }
}
