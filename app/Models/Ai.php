<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ai extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function isTarget()
    {
        return $this->is_hunt;
    }
}
