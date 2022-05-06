<?php

namespace App\Models;

use App\Ai\Services\BoardService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * @property string $opponent the opponent of the game.
 *
 * @author Dany Gagnon
 */
class Game extends Model
{
    use HasFactory, SoftDeletes;

    /** @var array nothing is guarded. */
    protected $guarded = [];

    /**
     * Returns a has-many relationship for having many games.
     *
     * @return HasMany a has-many relationship for having many
     * games.
     */
    public function missiles(): HasMany
    {
        return $this->hasMany(Missile::class);
    }

    /**
     * Returns all the coordinates for the boats on the board.
     *
     * @return Collection all the coordinates for the boats
     * on the board.
     */
    public function board(): Collection
    {
        $service = new BoardService($this);

        return $service->createGameBoard();
    }

    /**
     * Returns a has-many relationship for having many games.
     *
     * @return HasMany a has-many relationship for having many
     * games.
     */
    public function remainingBoats(): HasMany
    {
        return $this->hasMany(RemainingBoat::class);
    }

    /**
     * Returns a has-one relationship because a game has one
     * AI.
     *
     * @return HasOne a has-one relationship because a game
     * has one AI.
     */
    public function ai(): HasOne
    {
        return $this->hasOne(Ai::class);
    }

    /**
     * Returns a has-many relationship for having many games.
     *
     * @return HasMany a has-many relationship for having many
     * games.
     */
    public function stacks(): HasMany
    {
        return $this->hasMany(Stack::class);
    }

    /**
     * Returns the opponent of the game.
     *
     * @return string the opponent of the game
     */
    public function getOpponent(): string
    {
        return $this->opponent;
    }

    /**
     * The booted method makes sure to always assign the current
     * logged-in user to this game.
     */
    protected static function booted()
    {
        static::creating(function ($game) {
            $game->user_id = Auth::id();
        });
    }
}
