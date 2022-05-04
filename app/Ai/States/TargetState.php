<?php

namespace App\Ai\States;

use App\Ai\Facades\Ai;
use App\Ai\Grid;
use App\Ai\ShipAi;
use App\Ai\ShipState;
use App\Ai\Vector;
use App\Models\Bateau;
use App\Models\Partie;

class TargetState extends ShipState
{
    public function shoot(ShipAi $ship, Partie $partie): Vector
    {
        $sizes = $partie->remainingBoats()->get()
            ->map(fn ($boat) => Bateau::query()->where('id', $boat->bateau_id)->first()->size);
        $shots = $partie->missiles()->get()
            ->map(fn ($missile) => $missile->coordonnee);
        $grid = new Grid($sizes, $shots);

        $corners = $partie->stacks()->get()
            ->map(fn ($stack) => $stack->coord);

        $mix = $partie->stacks()->get()
            ->flatMap(fn ($stack) => [$stack->coord => $stack->weight]);

        $heatmap = $grid->targetmap($corners, $mix);

        $arr = $heatmap->sortDesc()->keys()->first();

        return Vector::make($arr);
    }
}
