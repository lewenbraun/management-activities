<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1;

use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_participants_with_activities(): void
    {
        Participant::factory()
            ->hasActivities(1, ['name' => 'Participant Activity'])
            ->create();

        $response = $this->getJson('/api/v1/participants');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.activities.0.name', 'Participant Activity');
    }

    public function test_it_returns_specific_participant_with_activities(): void
    {
        $participant = Participant::factory()
            ->hasActivities(1, ['name' => 'Specific Activity'])
            ->create();

        $response = $this->getJson("/api/v1/participants/{$participant->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $participant->name)
            ->assertJsonPath('data.activities.0.name', 'Specific Activity');
    }
}
