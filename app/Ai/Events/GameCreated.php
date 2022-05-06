<?php

namespace App\Ai\Events;

use App\Models\Game;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * The game created gets called when a game is created. I could
 * have used an observer, but I hate the way it doesn't mention
 * any call to create. It is not intuitive for the people that
 * will read this code.
 *
 * @author Dany Gagnon
 */
class GameCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Game the game that was created. */
    private Game $game;

    /**
     * Create a new event for when a game creates.
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * Returns the game of the event.
     *
     * @return Game the game of the event
     */
    public function getGame(): Game
    {
        return $this->game;
    }
}
