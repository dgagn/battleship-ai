<?php

namespace App\Ai\States;

use App\Ai\Grid;
use App\Ai\ShipAi;
use App\Ai\ShipState;
use App\Ai\Vector;
use App\Models\Boat;
use App\Models\Game;

class HuntingState extends ShipState
{
    public function shoot(ShipAi $ship, Game $partie): Vector
    {
        // todo: setup rajouter dans facade
        $sizes = $partie->remainingBoats()->get()
            ->map(fn ($boat) => Boat::query()->where('id', $boat->bateau_id)->first()->size);
        $shots = $partie->missiles()->get()
            ->map(fn ($missile) => $missile->coordonnee);
        $grid = new Grid($sizes, $shots);

        $heatmap = $grid->heatmaps();

        $parity = $sizes->sort()->first();
        $arr = $heatmap->sortDesc()->keys()
            ->skipUntil(function ($key) use ($parity) {
                $vec = Vector::make($key);

                return ($vec->getX() + $vec->getY()) % $parity == 0;
            })->first();

        if (! $arr) {
            return Vector::make($heatmap->sortDesc()->keys()->first());
        }

        return Vector::make($arr);
    }
}
