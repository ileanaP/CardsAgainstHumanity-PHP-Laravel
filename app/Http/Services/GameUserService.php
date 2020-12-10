<?php

/* namespace App\Http\Services;


class GameUserService 
{
    public function isEligible(Game $game, User $user)
    {
        $allUsersInCurrGame = $game->users()->get();

        if(count($allUsersInCurrGame) >= 6)
        {
            return $this->errorResponse('Game already has maximum nr of players.', 403);
        }

        if($user->in_game != null)
        {
            return $this->errorResponse('This user is playing another game.', 403);
        }
    }
} */