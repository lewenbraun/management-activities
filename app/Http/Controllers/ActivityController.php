<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(): View
    {
        $activities = Activity::with([
            'activityType',
            'creator',
            'participant',
            'media',
        ])->paginate(10);

        $favorites = [];
        if (auth()->check()) {
            /** @var User $user */
            $user = auth()->user();
            $favorites = $user->favoriteActivities->pluck('id')->toArray();
        }

        return view('activities.index', compact('activities', 'favorites'));
    }

    public function show(Activity $activity): View
    {
        $activity->load([
            'activityType',
            'creator',
            'participant',
            'media',
            'favoritedByUsers' => function (Builder $query): void {
                $query->when(auth()->check(), function (Builder $q): void {
                    $q->where('user_id', auth()->id());
                });
            },
        ]);

        return view('activities.show', compact('activity'));
    }

    public function toggleFavorite(Activity $activity): JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $attached = $user->favoriteActivities()->toggle($activity->id);
        $isFavorite = ! empty($attached['attached']);

        return response()->json(['isFavorite' => $isFavorite]);
    }
}
