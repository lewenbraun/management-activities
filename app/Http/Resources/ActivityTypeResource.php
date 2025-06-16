<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ActivityType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ActivityType
 */
class ActivityTypeResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon_name' => $this->icon_name,
            'display_order' => $this->display_order,
            'activities' => $this->whenLoaded('activities', fn (): array => $this->activities->map(fn ($activity): array => [
                'id' => $activity->id,
                'name' => $activity->name,
                'short_description' => $activity->short_description,
            ])->all()),
        ];
    }
}
