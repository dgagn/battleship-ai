<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stack extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getDirection()
    {
        return $this->direction;
    }

    public function getWeight()
    {
        return $this->weight;
    }
}
