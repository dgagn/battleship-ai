<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $name the name of the boat.
 * @property string $size the size of the boat.
 *
 * @author Dany Gagnon
 */
class Boat extends Model
{
    use HasFactory;

    /**
     * Returns the size of the boat.
     *
     * @return int the size of the boat
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Returns the name of the boat.
     *
     * @return string the name of the boat
     */
    public function getName(): string
    {
        return $this->name;
    }
}
