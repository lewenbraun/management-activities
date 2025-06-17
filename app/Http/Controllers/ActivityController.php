<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Repositories\ActivityRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function __construct(private readonly ActivityRepository $activityRepository) {}

    public function index(): View
    {
        $activities = $this->activityRepository->getAllActivitiesPaginated();

        $favorites = [];
        if (auth()->check()) {
            $favorites = $this->activityRepository->getUserFavoriteActivityIds((int) auth()->id())->toArray();
        }

        return view('activities.index', compact('activities', 'favorites'));
    }

    public function show(Activity $activity): View
    {
        $activity = $this->activityRepository->findActivityById($activity);

        return view('activities.show', compact('activity'));
    }

    public function toggleFavorite(Activity $activity): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $attached = $this->activityRepository->toggleFavoriteActivity((int) Auth::id(), $activity->id);
        $isFavorite = ! empty($attached['attached']);

        return response()->json(['isFavorite' => $isFavorite]);
    }
}
