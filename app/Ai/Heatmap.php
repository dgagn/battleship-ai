<?php

namespace App\Ai;

use App\Http\Controllers\PartieMissileController;
use App\Models\Bateau;
use App\Models\Partie;
use App\Models\Stack;

class Heatmap
{
    private Partie $partie;

    private int $parity = 2;

    /**
     * @param Partie $partie
     */
    public function __construct(Partie $partie)
    {
        $this->partie = $partie;
    }

    public function generate()
    {
        $heat = $this->generateEmptyHeatmap();
        $remaining = $this->partie->remainingBoats()->get();

        // if hunt mode, increment the weight of all directions

        if (Stack::query()->exists()) {
            $stack = Stack::query()->first();
            $vec = Vector::make($stack->coord);
            $stack->delete();

            return $vec;
        }

        foreach ($remaining as $bateau) {
            $b = Bateau::query()->find($bateau->bateau_id)->first();
            $heat = $this->ofSize($b->size, $heat);
        }

        $highest = $heat[0][0];
        for ($y = 0; $y < Board::SIZE; $y++) {
            for ($x = 0; $x < Board::SIZE; $x++) {
                if (($x + $y) % 2 === 0) {
                    continue;
                }
                if ($heat[$x][$y]->getWeight() > $highest->getWeight()) {
                    $highest = $heat[$x][$y];
                }
            }
        }

        if (! PartieMissileController::isValidCoord($highest->getVector(), $this->partie)) {
            return $this->generate();
        }

        return $highest->getVector();
    }

    private function generateEmptyHeatmap(): array
    {
        $heatmap = [];
        for ($y = 0; $y < Board::SIZE; $y++) {
            for ($x = 0; $x < Board::SIZE; $x++) {
                $vector = new Vector($x, $y);
                $probability = new Probability($vector);
                $heatmap[$x][$y] = $probability;
            }
        }

        return $heatmap;
    }

    private function ofSize($size, $heatmaps)
    {
        $directions = [
            Vector::right(),
            Vector::left(),
            Vector::up(),
            Vector::down(),
        ];

        for ($y = 0; $y < Board::SIZE; $y++) {
            for ($x = 0; $x < Board::SIZE; $x++) {
                foreach ($directions as $direction) {
                    $calc = $heatmaps[$x][$y];
                    $dirSize = $direction->mult($size);
                    $ship = $calc->getVector()->add($dirSize);

                    if (! $ship->within(0, 9)) {
                        continue;
                    }

                    $isValid = true;
                    $missiles = $this->partie->missiles()->get();
                    foreach ($missiles as $missile) {
                        $coord = Vector::make($missile->coordonnee);
                        if ($ship == $coord) {
                            $isValid = false;
                        }
                    }

                    if (! $isValid) {
                        continue;
                    }

                    $calc->increment();
                    while ($calc->getVector() != $ship) {
                        $vec = $calc->getVector()->add($direction);
                        $calc = $heatmaps[$vec->getX()][$vec->getY()];
                        $calc->increment();
                    }
                }
            }
        }

        return $heatmaps;
    }
}
