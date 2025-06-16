<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
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
            'website_link' => $this->website_link,
            'location_description' => $this->location_description,
            'coordinates' => $this->coordinates,
            'activities' => $this->whenLoaded('activities', fn () => $this->activities->map(fn ($activity): array => [
                'id' => $activity->id,
                'name' => $activity->name,
            ])),
        ];
    }
}
