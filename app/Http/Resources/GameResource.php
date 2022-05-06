<?php

namespace App\Http\Resources;

use App\Ai\Board;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'adversaire' => $this->opponent,
            'bateaux' => $this->board(),
            'created_at' => $this->created_at,
        ];
    }
}
