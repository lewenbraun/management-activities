<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_paginated_activities(): void
    {
        Activity::factory(20)->create();

        $response = $this->getJson('/api/v1/activities?per_page=5&page=2');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.current_page', 2)
            ->assertJsonPath('meta.total', 20);
    }

    public function test_it_uses_default_pagination_values(): void
    {
        Activity::factory(20)->create();

        $response = $this->getJson('/api/v1/activities');

        $response->assertStatus(200)
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('meta.current_page', 1);
    }

    public function test_it_returns_full_activity_details(): void
    {
        $activity = Activity::factory()
            ->for(ActivityType::factory(), 'activityType')
            ->for(User::factory(), 'creator')
            ->for(Participant::factory(), 'participant')
            ->create();

        $response = $this->getJson("/api/v1/activities/{$activity->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $activity->name)
            ->assertJsonPath('data.activity_type.name', $activity->activityType->name)
            ->assertJsonPath('data.participant.name', $activity->participant->name)
            ->assertJsonPath('data.creator.name', $activity->creator->name);
    }

    public function test_it_includes_relations_in_activity_details(): void
    {
        $activity = Activity::factory()
            ->for(ActivityType::factory(), 'activityType')
            ->for(Participant::factory(), 'participant')
            ->for(User::factory(), 'creator')
            ->create();

        $response = $this->getJson("/api/v1/activities/{$activity->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'activity_type' => ['id', 'name'],
                    'participant' => ['id', 'name'],
                    'creator' => ['id', 'name'],
                ],
            ]);
    }
}
