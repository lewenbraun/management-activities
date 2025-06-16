<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\PaginationPagesRequest;
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
     * @queryParam per_page integer Items per page. Example: 15
     * @queryParam page integer The page number. Example: 1
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Laravel Workshop",
     *       "description": "Learn Laravel basics and best practices.",
     *       "short_description": "Learn Laravel basics",
     *       "source": "https://www.google.com/",
     *       "activity_type": "Workshop",
     *       "participant": "Tech Company Inc."
     *     }
     *   ],
     *   "links": { ... },
     *   "meta": { ... }
     * }
     */
    public function index(PaginationPagesRequest $request): JsonResource
    {
        $perPage = $request->integer('per_page', 15);
        $page = $request->integer('page', 1);

        $activities = Activity::with(['activityType:id,name', 'participant:id,name'])
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

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
     *       "name": "John Doe"
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
