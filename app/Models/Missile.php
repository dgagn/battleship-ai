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
}
