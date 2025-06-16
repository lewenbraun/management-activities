<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1;

use App\Models\ActivityType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_activity_types_ordered(): void
    {
        ActivityType::factory()->create(['display_order' => 2]);
        ActivityType::factory()->create(['display_order' => 1]);

        $response = $this->getJson('/api/v1/activity-types');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.display_order', 1)
            ->assertJsonPath('data.1.display_order', 2);
    }

    public function test_it_returns_specific_activity_type_with_activities(): void
    {
        $type = ActivityType::factory()
            ->hasActivities(1, [
                'name' => 'Test Activity',
                'short_description' => 'Test Description',
            ])
            ->create();

        $response = $this->getJson("/api/v1/activity-types/{$type->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $type->name)
            ->assertJsonPath('data.activities.0.name', 'Test Activity');
    }
}
