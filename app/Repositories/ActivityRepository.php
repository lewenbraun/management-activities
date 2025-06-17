<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ActivityRepository
{
    /**
     * @return LengthAwarePaginator<int, Activity>
     */
    public function getAllActivitiesPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Activity::with([
            'activityType',
            'creator',
            'participant',
            'media',
        ])->paginate($perPage);
    }

    public function findActivityById(Activity $activity): Activity
    {
        return $activity->load([
            'activityType',
            'creator',
            'participant',
            'media',
        ]);
    }

    /**
     * @return Collection<int, int>
     */
    public function getUserFavoriteActivityIds(int $userId): Collection
    {
        /** @var User|null $user */
        $user = User::find($userId);

        if (! $user) {
            /** @var Collection<int, int> */
            return collect();
        }

        /** @var Collection<int, int> */
        return $user->favoriteActivities->pluck('id');
    }

    /**
     * @return array{attached: array<int, int>, detached: array<int, int>}
     */
    public function toggleFavoriteActivity(int $userId, int $activityId): array
    {
        /** @var User|null $user */
        $user = User::find($userId);

        if (! $user) {
            return ['attached' => [], 'detached' => [$activityId]];
        }

        /** @var array{attached: array<int, int>, detached: array<int, int>} */
        return $user->favoriteActivities()->toggle($activityId);
    }
}
