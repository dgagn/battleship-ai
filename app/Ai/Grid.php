<?php

namespace App\Ai;

use Illuminate\Support\Collection;

class Grid
{
    public const SIZE = 10;
    public const SHIP_WEIGHT = 200;

    private Collection $sizes;
    private Collection $shots;

    public function __construct(Collection $sizes, Collection $shots)
    {
        $this->sizes = $sizes;
        $this->shots = $shots;
    }

    public function heatmaps()
    {
        $heatmaps = $this->empty();
        foreach ($this->sizes as $size) {
            $heatmaps = $this->heatmap($heatmaps, $size);
        }

        return collect($heatmaps);
    }

    public function targetmap(Collection $corners, Collection $weighting)
    {
        $heatmaps = $this->heatmaps();
        foreach ($heatmaps as $coord => $value) {
            if ($corners->contains($coord) && $value > 0) {
                $heatmaps->put($coord, $value + self::SHIP_WEIGHT + ($weighting[$coord] ?? 0));
            }
        }

        return $heatmaps;
    }

    private function heatmap(array $heatmaps, int $size): array
    {
        $directions = Vector::directions();

        for ($y = 0; $y < self::SIZE; $y++) {
            for ($x = 0; $x < self::SIZE; $x++) {
                foreach ($directions as $direction) {
                    $vector = new Vector($x, $y);
                    $ship = $vector->add(
                        $direction->mult($size)
                    );

                    if (! $ship->within(0, 9) || $this->shots->filter(fn ($coord) => $coord == strval($ship) || $coord == strval($vector))->count() > 0) {
                        continue;
                    }

                    $isValid = true;
                    $newcalc = $vector->copy();
                    while (! $newcalc->equals($ship)) {
                        $newcalc = $newcalc->add($direction);
                        $filtered = $this->shots->filter(fn ($coord) => $coord == strval($newcalc));
                        if ($filtered->count() > 0) {
                            $isValid = false;
                            break;
                        }
                    }
                    if (! $isValid) {
                        continue;
                    }

                    $heatmaps[strval($vector)]++;
                    $vec = $vector->copy();
                    while (! $vec->equals($ship)) {
                        $vec = $vec->add($direction);
                        $heatmaps[strval($vec)]++;
                    }
                }
            }
        }

        return $heatmaps;
    }

    public function empty()
    {
        $heatmap = [];
        for ($y = 0; $y < self::SIZE; $y++) {
            for ($x = 0; $x < self::SIZE; $x++) {
                $heatmap[strval(new Vector($x, $y))] = 0;
            }
        }

        return $heatmap;
    }
}
