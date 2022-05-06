<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class GamePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view a game.
     *
     * @param User $user
     * @param Game $game
     * @return Response
     */
    public function view(User $user, Game $game)
    {
        return $user->id === $game->user_id
            ? Response::allow()
            : Response::deny('Cette action n’est pas autorisée.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Game $partie
     * @return Response|bool
     */
    public function delete(User $user, Game $partie): Response|bool
    {
        return $user->id === $partie->user_id
            ? Response::allow()
            : Response::deny('Cette action n’est pas autorisée.');
    }
}
