<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @group Activities Management
 *
 * Endpoints for managing activities and events.
 */
class ActivityController
{
    /**
     * Get all activities
     *
     * Returns a paginated list of all activities with essential details.
     *
     * @queryParam per_page integer Items per page. Example: 10
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Laravel Workshop",
     *       "short_description": "Learn Laravel basics",
     *       "activity_type": "Workshop",
     *       "participants": ["Tech Company Inc."]
     *     }
     *   ],
     *   "links": { ... },
     *   "meta": { ... }
     * }
     */
    public function index(): JsonResource
    {
        $perPage = request('per_page', 15);

        $activities = Activity::with(['activityType:id,name', 'participant:id,name'])
            ->latest()
            ->paginate($perPage);

        return ActivityResource::collection($activities);
    }

    /**
     * Get specific activity
     *
     * Returns full details of a single activity including participants and creator.
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "Laravel Workshop",
     *     "description": "Full description here...",
     *     "short_description": "Learn Laravel basics",
     *     "registration_link": "https://event.example.com/register",
     *     "coordinates": [40.7128, -74.0060],
     *     "schedule": {"start": "2023-06-20 10:00:00", "end": "2023-06-20 18:00:00"},
     *     "activity_type": {
     *       "id": 1,
     *       "name": "Workshop"
     *     },
     *     "creator": {
     *       "id": 1,
     *       "name": "Admin User"
     *     },
     *     "participant": {
     *         "id": 1,
     *         "name": "Tech Company Inc."
     *       }
     *    }
     * }
     */
    public function show(Activity $activity): JsonResource
    {
        $activity->load([
            'activityType:id,name',
            'creator:id,name',
            'participant:id,name',
        ]);

        return new ActivityResource($activity);
    }
}
