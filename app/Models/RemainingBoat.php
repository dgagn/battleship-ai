<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The remaining boats on the board.
 *
 * @author Dany Gagnon
 */
class RemainingBoat extends Model
{
    use HasFactory;

    /** @var array nothing is guarded. */
    protected $guarded = [];

    /**
     * Returns a has-many relationship for having many games.
     *
     * @return HasMany a has-many relationship for having many
     * games.
     */
    public function boats(): HasMany
    {
        return $this->hasMany(Boat::class);
    }
}
