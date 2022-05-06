<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ai extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function isTargetMode()
    {
        return $this->hits !== 0;
    }

    public function isHuntMode()
    {
        return $this->hits === 0;
    }

    public function getHits()
    {
        return $this->hits;
    }
}
