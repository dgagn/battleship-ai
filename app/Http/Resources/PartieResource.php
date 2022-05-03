<?php

namespace App\Http\Resources;

use App\Ai\Board;
use Illuminate\Http\Resources\Json\JsonResource;

class PartieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'adversaire' => $this->adversaire,
            'bateaux' => $this->boats(),
            'created_at' => $this->created_at,
        ];
    }
}
