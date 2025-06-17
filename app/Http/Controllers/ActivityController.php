<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with([
            'activityType',
            'creator',
            'participant',
            'media'
        ])->paginate(10);

        $favorites = auth()->check()
            ? auth()->user()->favoriteActivities->pluck('id')->toArray()
            : [];

        return view('activities.index', compact('activities', 'favorites'));
    }

    public function show(Activity $activity)
    {
        $activity->load([
            'activityType',
            'creator',
            'participant',
            'media',
            'favoritedByUsers' => function ($query) {
                $query->when(auth()->check(), function ($q) {
                    $q->where('user_id', auth()->id());
                });
            }
        ]);

        return view('activities.show', compact('activity'));
    }

    public function toggleFavorite(Activity $activity)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $attached = $user->favoriteActivities()->toggle($activity->id);
        $isFavorite = !empty($attached['attached']);

        return response()->json(['isFavorite' => $isFavorite]);
    }
}
