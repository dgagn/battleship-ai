<?php

namespace App\AiV2;

use Illuminate\Support\Collection;

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
    /**
     * @var int The horizontal x position of the vector.
     */
    private int $dx;

    /**
     * @var int The vertical y position of the vector.
     */
    private int $dy;

    /**
     * Constructs a new vector with the specified x and y.
     *
     * @param int x the horizontal value of the vector
     * @param int y the vertical value of the vector
     */
    public function __construct(int $dx, int $dy)
    {
        $this->dx = $dx;
        $this->dy = $dy;
    }

    /**
     * Constructs a new vector with the specified notation
     * for battleship.
     *
     * @param string the battleship notation
     */
    public static function from(string $notation): self
    {
        $arr = explode('-', $notation);
        $dy = ord($arr[0]) - 65;
        $dx = $arr[1] - 1;

        return new self($dx, $dy);
    }

    /**
     * Returns the up direction for a vector.
     *
     * @return self the up direction for a vector.
     */
    public static function up(): self
    {
        return new self(0, -1);
    }

    /**
     * Returns the down direction for a vector.
     *
     * @return self the down direction for a vector.
     */
    public static function down(): self
    {
        return new self(0, 1);
    }

    /**
     * Returns the left direction for a vector.
     *
     * @return self the left direction for a vector.
     */
    public static function left(): self
    {
        return new self(-1, 0);
    }

    /**
     * Returns the right direction for a vector.
     *
     * @return self the right direction for a vector.
     */
    public static function right(): self
    {
        return new self(1, 0);
    }

    /**
     * Returns if the current vector is matching a parity of
     * a given size.
     *
     * @param int $parity the parity of a vector
     * @return bool if the current vector is matching a parity of
     * a given size.
     */
    public function parity(int $parity): bool
    {
        return ($this->dx + $this->dy) % $parity === 0;
    }

    /**
     * Returns a collection with all the directions.
     *
     * @return Collection a collection with all the directions.
     */
    public static function directions(): Collection
    {
        return collect([
            self::up(),
            self::down(),
            self::left(),
            self::right(),
        ]);
    }

    /**
     * Returns a new vector with the sum of the current and
     * the specified one.
     *
     * @param Vector vec the vector to add
     * @return Vector a new vector with the sum of the current and
     * the specified one.
     */
    public function add(self $vec): self
    {
        return new self($this->dx + $vec->dx, $this->dy + $vec->dy);
    }

    /**
     * Returns a new vector with the subtracted vector.
     *
     * @param Vector vec the vector to subtract
     * @return Vector a new vector with the subtracted x and y
     */
    public function sub(self $vec): self
    {
        return new self($this->dx - $vec->dx, $this->dy - $vec->dy);
    }

    /**
     * Return the hypotenuse of the vector. Also noted by sqrt(x^2+y^2).
     * Gets the length of the long side of a triangle.
     *
     * @return int the hypotenuse of the vector
     */
    public function len(): int
    {
        return (int) hypot($this->dx, $this->dy);
    }

    /**
     * Returns the distance between the current vector
     * and a vector.
     *
     * @param Vector vec the vector to calculate the distance from
     * @return int the distance between the current vector
     * and a vector.
     */
    public function dist(self $vec): int
    {
        return $this->sub($vec)->len();
    }

    /**
     * Returns true if the current vector x and y is
     * within a given lower and upper bound.
     *
     * @param int lower the lower bound
     * @param int upper the upper bound
     * @return bool if the current vector's x and y is
     * within a given lower and upper bound.
     */
    public function within(int $lower, int $upper): bool
    {
        return $this->dx >= $lower && $this->dx <= $upper && $this->dy >= $lower && $this->dy <= $upper;
    }

    /**
     * Returns the horizontal x position of the vector.
     *
     * @return int the horizontal x position of the vector.
     */
    public function getX(): int
    {
        return $this->dx;
    }

    /**
     * Returns the vertical y position of the vector.
     *
     * @return int the vertical y position of the vector.
     */
    public function getY(): int
    {
        return $this->dy;
    }

    /**
     * Returns the vectors' x and y multiplied by a scalar
     * value.
     *
     * @param int $size the scalar to multiply the vector by
     * @return Vector the vectors' x and y multiplied by a scalar
     * value.
     */
    public function mult(int $size)
    {
        return new self($this->dx * $size, $this->dy * $size);
    }

    /**
     * Returns a copied vector of the current vector.
     *
     * @return Vector a copied vector of the current vector
     */
    public function copy(): self
    {
        return new self($this->dx, $this->dy);
    }

    /**
     * Returns true if a vector is equal to another.
     *
     * @param Vector $vector the vector to compare to self
     * @return bool true if a vector is equal to another.
     */
    public function equals(self $vector): bool
    {
        return strval($this) == strval($vector);
    }

    /**
     * Returns a string representation of a coordinate vector
     * position.
     *
     * @param int $x the x (horizontal) position of the vector
     * @param int $y the y (vertical) position of the vector
     * @return string a string representation of a coordinate
     * vector position.
     */
    public static function string(int $x, int $y): string
    {
        return strval(new self($x, $y));
    }

    /**
     * Returns a string representation of the `Vector` for the
     * battleship game.
     *
     * @return string a string representation of the `Vector`
     * class
     */
    public function __toString(): string
    {
        return chr(65 + $this->dy).'-'.($this->dx + 1);
    }

    /**
     * Returns a string representation of the `Vector` for the
     * battleship game.
     *
     * @return string a string representation of the `Vector`
     * class
     */
    public function str(): string
    {
        return strval($this);
    }
}
