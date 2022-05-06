<?php

namespace App\Models;

use App\Ai\Board;
use App\AiV2\Services\BoardService;
use App\AiV2\Services\GameService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function missiles(): HasMany
    {
        return $this->hasMany(Missile::class);
    }

    public function board(): Collection
    {
        $service = new BoardService($this);
        return $service->createGameBoard();
    }

    public function remainingBoats(): HasMany
    {
        return $this->hasMany(RemainingBoat::class);
    }

    public function ai(): HasOne
    {
        return $this->hasOne(Ai::class);
    }

    public function stacks(): HasMany
    {
        return $this->hasMany(Stack::class);
    }

    protected static function booted()
    {
        static::creating(function ($game) {
            $game->user_id = Auth::id();
        });
    }
}
