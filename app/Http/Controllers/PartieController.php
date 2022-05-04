<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartieRequest;
use App\Http\Resources\PartieResource;
use App\Models\Ai;
use App\Models\Bateau;
use App\Models\Partie;

class PartieController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param PartieRequest $request
     * @return PartieResource
     */
    public function store(PartieRequest $request)
    {
        $partie = Partie::query()->create($request->validated());

        $boats = Bateau::all();
        foreach ($boats as $boat) {
            $partie->remainingBoats()->create([
                'bateau_id' => $boat->id,
            ]);
        }

        Ai::query()->create([
            'partie_id' => $partie->id,
            'is_target' => false,
        ]);

        return new PartieResource($partie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partie  $partie
     * @return PartieResource
     */
    public function destroy(Partie $partie)
    {
        $this->authorize('delete', $partie);
        $partie->delete();

        return new PartieResource($partie);
    }
}
