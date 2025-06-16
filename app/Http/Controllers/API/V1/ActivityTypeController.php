<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\ActivityTypeResource;
use App\Models\ActivityType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @group Activity Types Management
 *
 * Endpoints for managing activity types.
 */
class ActivityTypeController
{
    /**
     * Get all activity types
     *
     * Returns a list of all activity types with their details.
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Workshop",
     *       "icon_name": "icons/workshop.svg",
     *       "display_order": 1
     *     }
     *   ]
     * }
     */
    public function index(): JsonResource
    {
        $actvivtyTypes = ActivityType::orderBy('display_order')->get();

        return ActivityTypeResource::collection($actvivtyTypes);
    }

    /**
     * Get specific activity type
     *
     * Returns details of a single activity type including related activities.
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "Workshop",
     *     "icon_name": "icons/workshop.svg",
     *     "display_order": 1,
     *     "activities": [
     *       {
     *         "id": 1,
     *         "name": "Laravel Workshop",
     *         "short_description": "Learn Laravel basics"
     *       }
     *     ]
     *   }
     * }
     */
    public function show(ActivityType $activityType): JsonResource
    {
        $activityType->load('activities:id,name,short_description,activity_type_id');

        return new ActivityTypeResource($activityType);
    }
}
