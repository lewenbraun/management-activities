<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'registration_link' => $this->registration_link,
            'coordinates' => $this->coordinates,
            'schedule' => $this->schedule,
            'activity_type' => [
                'id' => $this->activityType->id,
                'name' => $this->activityType->name,
            ],
            'creator' => [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
            ],
            'participant' => [
                'id' => $this->participant->id,
                'name' => $this->participant->name,
            ],
        ];
    }
}
