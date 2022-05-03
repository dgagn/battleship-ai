<?php

namespace App\Ai;

use App\Models\Bateau;

class Board
{
    public const SIZE = 10;

    public function init(): array
    {
        $boats = Bateau::all();
        $arr = [];
        foreach ($boats as $boat) {
            $pos = Possibility::factory($boat->size, $boat->name);
            foreach ($arr as $a) {
                while ($pos->isOverlapping($a) || ! $pos->isValidCoord()) {
                    $pos = Possibility::factory($boat->size, $boat->name);
                }
            }
            $arr[] = $pos;
        }

        $newarr = [];
        foreach ($arr as $a) {
            $newarr[$a->getName()] = $a->getCoordsValue();
        }

        return $newarr;
    }

    public function getPossibleVectors()
    {
        $vectors = [];
        for ($y = 0; $y < self::SIZE; $y++) {
            for ($x = 0; $x < self::SIZE; $x++) {
                if (($x + $y) % 2 == 0) {
                    continue;
                }
                $vectors[] = new Vector($x, $y);
            }
        }

        return $vectors;
    }

    public function getUnpossibleVectors()
    {
        $vectors = [];
        for ($y = 0; $y < self::SIZE; $y++) {
            for ($x = 0; $x < self::SIZE; $x++) {
                if (($x + $y) % 2 !== 0) {
                    continue;
                }
                $vectors[] = new Vector($x, $y);
            }
        }

        return $vectors;
    }
}
