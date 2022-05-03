<?php

namespace App\Ai;

class Probability
{
    private int $weight = 0;

    private Vector $vector;

    /**
     * @param Vector $vector
     */
    public function __construct(Vector $vector)
    {
        $this->vector = $vector;
    }

    public function increment()
    {
        $this->weight++;
    }

    public function reset()
    {
        $this->weight = 0;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @return Vector
     */
    public function getVector(): Vector
    {
        return $this->vector;
    }
}
