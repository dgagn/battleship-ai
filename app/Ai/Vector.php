<?php

namespace App\Ai;

/**
 * The `Vector` class is a utility class to make calculations
 * between an x and a y position. The `Vector` is
 * represented as YX where Y is ranging from A-J, and specified
 * in an integer precision.
 *
 * @author danygagnon
 */
class Vector
{
    private int $dx;
    private int $dy;

    public function __construct(int $dx, int $dy)
    {
        $this->dx = $dx;
        $this->dy = $dy;
    }

    public function add(Vector $vec): Vector
    {
        return new Vector($this->dx + $vec->dx, $this->dy + $vec->dy);
    }

    public function getX(): int
    {
        return $this->dx;
    }

    public function getY(): int
    {
        return $this->dy;
    }

    public static function up(): Vector
    {
        return new Vector(0, -1);
    }

    public static function down(): Vector
    {
        return new Vector(0, 1);
    }

    public static function left(): Vector
    {
        return new Vector(-1, 0);
    }

    public static function right(): Vector
    {
        return new Vector(1, 0);
    }

    public function __toString(): string
    {
        return chr(65 + $this->dy) . $this->dx;
    }
}
