<?php

namespace App\Ai\Events;

use App\Models\Game;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Game $game;

    /**
     * Create a new event for when a missile updates.
     *
     * @return void
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getGame(): Game
    {
        return $this->game;
    }
}
