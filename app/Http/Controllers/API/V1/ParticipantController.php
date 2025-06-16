<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\ParticipantResource;
use App\Models\Participant;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @group Participants Management
 *
 * Endpoints for managing event participants.
 */
class ParticipantController
{
    /**
     * Get all participants
     *
     * Returns a list of all participants with their details.
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Tech Company Inc.",
     *       "website_link": "https://tech.example.com",
     *       "location_description": "New York",
     *       "coordinates": [40.7128, -74.0060]
     *     }
     *   ]
     * }
     */
    public function index(): JsonResource
    {
        $participants = Participant::with('activities:id,name,participant_id')->get();

        return ParticipantResource::collection($participants);
    }

    /**
     * Get specific participant
     *
     * Returns details of a single participant including their activities.
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "Tech Company Inc.",
     *     "website_link": "https://tech.example.com",
     *     "location_description": "New York",
     *     "coordinates": [40.7128, -74.0060],
     *     "activities": [
     *       {
     *         "id": 1,
     *         "name": "Laravel Workshop"
     *       }
     *     ]
     *   }
     * }
     */
    public function show(Participant $participant): JsonResource
    {
        $participant->load('activities:id,name,participant_id');

        return new ParticipantResource($participant);
    }
}
