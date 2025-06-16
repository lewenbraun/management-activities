<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\ActivityController;
use App\Http\Controllers\API\V1\ActivityTypeController;
use App\Http\Controllers\API\V1\ParticipantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');
Route::prefix('v1')->group(function (): void {
    // Activity Types
    Route::get('activity-types', ActivityTypeController::class.'@index');
    Route::get('activity-types/{activityType}', ActivityTypeController::class.'@show');

    // Participants
    Route::get('participants', ParticipantController::class.'@index');
    Route::get('participants/{participant}', ParticipantController::class.'@show');

    // Activities
    Route::get('activities', ActivityController::class.'@index');
    Route::get('activities/{activity}', ActivityController::class.'@show');
});
