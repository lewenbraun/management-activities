<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\ActivityController;
use App\Http\Controllers\API\V1\ActivityTypeController;
use App\Http\Controllers\API\V1\ParticipantController;
use Illuminate\Support\Facades\Route;

Route::controller(ActivityTypeController::class)->prefix('activity-types')->group(function (): void {
    Route::get('/', 'index');
    Route::get('/{activityType}', 'show');
});

Route::controller(ParticipantController::class)->prefix('participants')->group(function (): void {
    Route::get('/', 'index');
    Route::get('/{participant}', 'show');
});

Route::controller(ActivityController::class)->prefix('activities')->group(function (): void {
    Route::get('/', 'index');
    Route::get('/{activity}', 'show');
});
