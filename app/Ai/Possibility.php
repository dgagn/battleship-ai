<?php

namespace App\Ai;

class Possibility
{
    private Vector $direction;

    private Vector $vector;

    private int $size;

    private string $name;

    public function __construct(Vector $direction, Vector $vector, int $size, string $name)
    {
        $this->direction = $direction;
        $this->vector = $vector;
        $this->size = $size;
        $this->name = $name;
    }

    public static function factory(int $size, string $name): self
    {
        $directions = [
            Vector::up(),
            Vector::down(),
            Vector::left(),
            Vector::right(),
        ];
        $vec = new Vector(rand(0, 9), rand(0, 9));
        $direction = $directions[array_rand($directions)];
        $pos = new self($direction, $vec, $size, $name);
        while (! $pos->isValidCoord()) {
            return self::factory($size, $name);
        }

        return $pos;
    }

    public function isValidCoord(): bool
    {
        $vec = $this->vector;
        $dirup = $this->direction->mult($this->size);
        $new = $vec->add($dirup);
        if ($new->within(0, 9)) {
            return true;
        }

        return false;
    }

    public function isOverlapping(self $vector): bool
    {
        foreach ($this->getCoords() as $c) {
            foreach ($vector->getCoords() as $c2) {
                if (strcmp(strval($c), strval($c2)) === 0) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getCoords(): array
    {
        $pos = [];
        $prev = $this->vector;
        for ($i = 0; $i < $this->size; $i++) {
            $pos[] = $prev;
            $prev = $prev->add($this->direction);
        }

        return $pos;
    }

    public function getCoordsValue(): array
    {
        $pos = [];
        $prev = $this->vector;
        for ($i = 0; $i < $this->size; $i++) {
            $pos[] = strval($prev);
            $prev = $prev->add($this->direction);
        }

        return $pos;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
