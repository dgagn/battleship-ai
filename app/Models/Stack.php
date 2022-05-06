<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

/**
 * The stack is all the moves that are weighted higher than the
 * normal coordinates.
 *
 * @property int $weight the weight associated with a coordinate
 * on the stack
 * @property int $direction the direction associated with a
 * coordinate on the stack
 *
 * @author Dany Gagnon
 */
class Stack extends Model
{
    use HasFactory, SoftDeletes;

    /** @var array nothing is guarded. */
    protected $guarded = [];

    /**
     * Returns the direction axis, for exemple, horizontal
     * or vertical.
     *
     * @return int the direction axis, for exemple, horizontal
     * or vertical.
     *
     * @see Config for information about the integer mapping
     */
    public function getDirection(): int
    {
        return $this->direction;
    }

    /**
     * Returns the weight associated with the stack.
     *
     * @return int the weight associated with the stack.
     */
    public function getWeight(): int
    {
        return $this->weight;
    }
}
