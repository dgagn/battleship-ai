<?php

namespace App\Ai;

class Position
{
    private Vector $position;
    private int $size;
    private Vector $direction;

    public function __construct(Vector $position, Vector $direction, int $size)
    {
        $this->position = $position;
        $this->size = $size;
        $this->direction = $direction;
    }

    public function getCoords(): array
    {
        $pos = [];
        $prev = $this->position;
        for ($i = 0; $i < $this->size; $i++) {
            $pos[] = $prev;
            $prev = $this->position->add($this->direction);
        }
        return $pos;
    }
}
