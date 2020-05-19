<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;
use App\User;
use Illuminate\Support\Facades\DB;

class GameUserController extends ApiController
{
    public function index(Game $game)
    {

        $users = $game->users()->get();
        return $this->showAll($users);
    }

    public function show(Game $game, User $user)
    {
        $users = $game->users()->get();

        if($users->contains($user))
        {
            return $this->showOne($user);
        }
        else
        {
            if(count($users) >= 6)
            {
                return $this->errorResponse('Game already has maximum nr of players.', 403);
            }

            if($user->in_game != null)
            {
                return $this->errorResponse('This user is playing another game.', 403);
            }

            $user->in_game = $game->id;
            $user->save();
            $game->users()->save($user);
            return $this->showOne($user);
        }

        return $this->errorResponse('This user is not playing in this game.', 403);
    }

    public function remove($game_id, $user_id)
    {
        $game = Game::find($game_id);
        $user = User::find($user_id);

        $game->users()->detach($user);
        $user->in_game = null;
        $user->save();

        return $user;
    }
}