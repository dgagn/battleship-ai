<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * A game policy is associated with the authorize function for
 * a game.
 *
 * @author Dany Gagnon
 */
class GamePolicy
{
    use HandlesAuthorization;

    public const UNAUTHORIZED = 'Cette action n’est pas autorisée.';

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
            : Response::deny(self::UNAUTHORIZED);
    }

    /**
     * Determine whether the user can delete the game.
     *
     * @param User $user the user
     * @param Game $game the game
     * @return Response|bool
     */
    public function delete(User $user, Game $game): Response|bool
    {
        return $user->id === $game->user_id
            ? Response::allow()
            : Response::deny(self::UNAUTHORIZED);
    }
}
