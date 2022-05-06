<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * The missile that was launched by us.
 *
 * @property int $result the result is all shown in the Config
 * @property string $coordinate the coordinate of the missile
 *
 * @author Dany Gagnon
 */
class Missile extends Model
{
    use HasFactory;

    /** @var array nothing is guarded. */
    protected $guarded = [];

    /**
     * Returns the result of the shot.
     *
     * @return int the result of the shot
     */
    public function getResult(): int
    {
        return $this->result;
    }

    /**
     * Returns the coordinate of the missile.
     *
     * @return string the coordinate of the missile
     */
    public function getCoordinate(): string
    {
        return $this->coordinate;
    }

    /**
     * Returns a belongs-to relationship because a missile
     * belongs to a game.
     *
     * @return BelongsTo a belongs-to relationship because a missile
     * belongs to a game.
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Returns a has-one-through relationship because a missile
     * was shot by a given user.
     *
     * @return HasOneThrough a has-one-through relationship
     * because a missile was shot by a given user.
     */
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Game::class);
    }
}
