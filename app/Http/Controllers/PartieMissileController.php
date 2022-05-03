<?php

namespace App\Http\Controllers;

use App\Ai\Direction;
use App\Ai\Facades\Ai;
use App\Ai\Grid;
use App\Ai\Vector;
use App\Http\Requests\UpdatePartieMissileRequest;
use App\Http\Resources\PartieMissileResource;
use App\Models\Bateau;
use App\Models\Missile;
use App\Models\Partie;

class PartieMissileController extends Controller
{
    public function store(Partie $partie)
    {
        $this->authorize('view', $partie);

        $ai = $partie->ai()->first();

        if ($ai->is_hunt) {
            Ai::target();
        } else {
            Ai::hunting();
        }

        $try = Ai::shoot($partie);

        $missile = $partie->missiles()->create([
            'coordonnee' => strval($try),
        ]);

        return new PartieMissileResource($missile);
    }

    public function update(UpdatePartieMissileRequest $request, Partie $partie, $coord)
    {
        $request->validated();
        $resultat = (int) $request->get('resultat');
        $this->authorize('view', $partie);
        $missile = Missile::findCoordByGame($coord, $partie);
        $missile->resultat = $resultat;
        $missile->save();
        $ai = $partie->ai()->first();

        if ($resultat === 1) {
            $directions = Vector::directions();
            $vec = Vector::make($coord);
            $sizes = $partie->remainingBoats()->get()
                ->map(fn ($boat) => Bateau::query()->where('id', $boat->bateau_id)->first()->size);
            $shots = $partie->missiles()->get()
                ->map(fn ($missile) => $missile->coordonnee);
            $grid = new Grid($sizes, $shots);

            $heatmap = $grid->heatmaps();

            foreach ($directions as $direction) {
                $newvec = $vec->add($direction);
                if (!$newvec->within(0, 9)) {
                    continue;
                }
                $heat = $heatmap->get(strval($newvec));

                if ($heat === 0) {
                    continue;
                }

                $dir = $direction == Vector::up() ||
                    $direction == Vector::down() ? Direction::Y
                    : Direction::X;

                $partie->stacks()->updateOrCreate([
                    'coord' => strval($newvec),
                ], [
                    'weight' => $heat,
                    'dir' => $dir,
                ]);
            }

            $stack = $partie->stacks()->where('coord', $coord)->first();

            if ($stack) {
                $applyweight = $partie->stacks()->where('dir', $stack->dir)->get();
                $applyweight->each(fn ($stack) => $stack->update([
                    'weight' => $stack->weight + 100
                ]));
            }

            $ai->hits++;
            $ai->is_hunt = true;
            $ai->save();
        } else if ($resultat > 1) {
            $partie->remainingBoats()
                ->where('bateau_id', $resultat - 1)
                ->first()
                ->delete();
            $size = Bateau::query()
                ->where('id', $resultat - 1)
                ->first()
                ->size;

            $ai->hits -= $size - 1;
            $ai->save();

            $stacks = $partie->stacks()->get();

            if ($ai->hits == 0) {
                $ai->is_hunt = false;
                $ai->save();
                $stacks->each(function($stack) {
                    $stack->delete();
                });
            }

            // cleanup
            $stacks->each(function($stack) {
                $hasMissiles = Missile::query()->where('coordonnee', $stack->coord)->exists();
                if ($hasMissiles) {
                    $stack->delete();
                }
            });
        }

        return new PartieMissileResource($missile);
    }

    public static function isValidCoord(Vector $coord, Partie $partie)
    {
        $missiles = Missile::all();
        foreach ($missiles as $missile) {
            if ($missile->coord == strval($coord)) {
                return false;
            }
        }
        if (! $coord->within(0, 9)) {
            return false;
        }

        return true;
    }
}
