<?php

use App\Ai\Heatmap;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameMissileController;
use App\Http\Resources\HeatmapResource;
use App\Models\Game;
use Illuminate\Support\Facades\Route;

Route::prefix('/parties')
    ->controller(GameController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/', 'store');
        Route::delete('/{game}', 'destroy');
    });

Route::prefix('/parties')
    ->controller(GameMissileController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/{game}', 'show');
        Route::post('/{game}/missiles', 'store');
        Route::put('/{game}/missiles/{missile:coordinate}', 'update')
            ->scopeBindings();
    });
