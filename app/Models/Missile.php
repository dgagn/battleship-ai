<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Missile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Partie::class);
    }

    public function partie(): BelongsTo
    {
        return $this->belongsTo(Partie::class);
    }

    public static function findCoordByGame($coord, Partie $partie)
    {
        $missile = self::query()
            ->where([
                ['coordonnee', $coord],
                ['partie_id', $partie->id],
            ]);
        if (! $missile->exists()) {
            throw new NotFoundHttpException('La ressource n’existe pas.');
        }

        return $missile->first();
    }

    public static function existsByCoordGame($coord, Partie $partie)
    {
        $missile = self::query()
            ->where([
                ['coordonnee', $coord],
                ['partie_id', $partie->id],
            ]);

        return $missile->exists();
    }
}
