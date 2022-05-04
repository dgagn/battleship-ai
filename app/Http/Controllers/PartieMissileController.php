<?php

namespace App\Http\Controllers;

use App\Ai\Direction;
use App\Ai\Facades\Ai;
use App\Ai\Grid;
use App\Ai\Vector;
use App\Http\Requests\UpdatePartieMissileRequest;
use App\Http\Resources\PartieMissileResource;
use App\Models\Bateau;
use App\Models\Partie;

class PartieMissileController extends Controller
{
    public function store(Partie $partie)
    {
        $this->authorize('view', $partie);

        $ai = $partie->ai()->first();

        if ($ai->is_target) {
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
        $missile = $partie->missiles()->where('coordonnee', $coord)->firstOrFail();

        $missile->update([
            'resultat' => $resultat,
        ]);

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
                if (! $newvec->within(0, 9)) {
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

            if ($ai->is_target) {
                $stackers = $partie->stacks()->withTrashed()->where('coord', $coord);
                if ($stackers->exists()) {
                    $stacksOfDirection = $partie->stacks()->where('dir', $stackers->first()->dir)->get();
                    foreach ($stacksOfDirection as $sod) {
                        $sod->weight += 100;
                        $sod->save();
                    }
                }
            }

            $ai->hits++;
            $ai->is_target = true;
            $ai->save();
        } elseif ($resultat > 1) {
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

            if ($ai->hits == 0) {
                $ai->is_target = false;
                $ai->save();
                $partie->stacks()->each(function ($stack) use ($coord) {
                    if ($stack->coord != $coord) {
                        $stack->delete();
                    }
                });
            }
        }

        return new PartieMissileResource($missile);
    }

    public function show(Partie $partie)
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

        return $heatmap->toJson();
    }
}
