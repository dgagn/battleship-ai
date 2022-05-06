<?php

namespace App\Ai\Events;

use App\Models\Missile;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MissileUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Missile the missile that was updated */
    private Missile $missile;

    /**
     * Create a new event for when a missile updates.
     *
     * @return void
     */
    public function __construct(Missile $missile)
    {
        $this->missile = $missile;
    }

    /**
     * @return Missile
     */
    public function getMissile(): Missile
    {
        return $this->missile;
    }
}
