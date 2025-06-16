<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Participant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            ShieldSeeder::class,
        ]);

        User::factory(10)->create();

        ActivityType::factory(5)->create();

        Participant::factory(8)->create();

        Activity::factory(20)->create()->each(function (Activity $activity): void {
            $users = User::inRandomOrder()->limit(rand(0, 5))->get();
            $activity->favoritedByUsers()->attach($users);
        });
    }
}
