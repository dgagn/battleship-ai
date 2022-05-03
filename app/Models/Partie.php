<?php

namespace App\Models;

use App\Ai\Board;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Partie extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function missiles(): HasMany
    {
        return $this->hasMany(Missile::class);
    }

    public function boats()
    {
        return (new Board)->init();
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
        static::creating(function ($partie) {
            $partie->user_id = Auth::id();
        });
    }
}
