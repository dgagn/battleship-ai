<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Missile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getResult()
    {
        return $this->result;
    }

    public function getCoordinate()
    {
        return $this->coordinate;
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Game::class);
    }
}
