<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $hits the hits that the AI calculated
 *
 * @author Dany Gagnon
 */
class Ai extends Model
{
    use HasFactory;

    /** @var array nothing is guarded. */
    protected $guarded = [];

    /**
     * Returns true if the AI is in the target mode.
     *
     * @return bool true if the AI is in the target mode.
     */
    public function isTargetMode(): bool
    {
        return $this->hits !== 0;
    }

    /**
     * Returns true if the AI is in the hunting mode.
     *
     * @return bool true if the AI is in the hunting mode.
     */
    public function isHuntMode(): bool
    {
        return $this->hits === 0;
    }

    /**
     * Returns the hits counter on all the boats.
     *
     * @return int the hits counter on all the boats
     */
    public function getHits(): int
    {
        return $this->hits;
    }
}
