<?php

namespace App\AiV2\Listeners;

use App\AiV2\Events\MissileUpdated;

class MissileStackListener
{
    /**
     * Handle the event.
     *
     * @param MissileUpdated $event
     * @return void
     */
    public function handle(MissileUpdated $event)
    {
        $missile = $event->getMissile();
        $game = $missile->game()->first();
        $ai = $game->ai()->first();

        dd($ai);
    }
}
