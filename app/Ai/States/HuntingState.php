<?php

namespace App\Ai\States;

use App\Ai\Grid;
use App\Ai\ShipAi;
use App\Ai\ShipState;
use App\Ai\Vector;
use App\Models\Bateau;
use App\Models\Partie;

class HuntingState extends ShipState
{
    public function shoot(ShipAi $ship, Partie $partie): Vector
    {
        // todo: setup rajouter dans facade
        $sizes = $partie->remainingBoats()->get()
            ->map(fn ($boat) => Bateau::query()->where('id', $boat->bateau_id)->first()->size);
        $shots = $partie->missiles()->get()
            ->map(fn ($missile) => $missile->coordonnee);
        $grid = new Grid($sizes, $shots);

        $heatmap = $grid->heatmaps();
        $arr = $heatmap->sortDesc()->keys()->first();

        return Vector::make($arr);
    }
}