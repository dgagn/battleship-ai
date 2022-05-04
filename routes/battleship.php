<?php

use App\Ai\Heatmap;
use App\Http\Controllers\PartieController;
use App\Http\Controllers\PartieMissileController;
use App\Http\Resources\HeatmapResource;
use App\Models\Partie;
use Illuminate\Support\Facades\Route;

Route::prefix('/parties')
    ->controller(PartieController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/', 'store');
        Route::delete('/{partie}', 'destroy');
    });

Route::prefix('/parties')
    ->controller(PartieMissileController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/{partie}', 'show');
        Route::post('/{partie}/missiles', 'store');
        Route::put('/{partie}/missiles/{coord}', 'update');
    });
