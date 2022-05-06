<?php

namespace App\Ai\Events;

use App\Models\Missile;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * The event called when a missile is updated with a result.
 *
 * @author Dany Gagnon
 */
class MissileUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Missile the missile that was updated */
    private Missile $missile;

    /**
     * Create a new event for when a missile updates.
     */
    public function __construct(Missile $missile)
    {
        $this->missile = $missile;
    }

    /**
     * Returns the missile associated with the event.
     *
     * @return Missile the missile associated with the event
     */
    public function getMissile(): Missile
    {
        return $this->missile;
    }
}
