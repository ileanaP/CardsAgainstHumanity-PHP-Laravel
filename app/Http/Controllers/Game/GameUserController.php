<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Events\PlayerEnter;
use App\Events\PlayerLeave;

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

        if(!($users->contains($user)))
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

            event(new PlayerEnter($user));

            $user->save();
            $game->users()->save($user);
        }

        if($user->in_game != null)
        {
            return $this->showOne($user);
        }
        else
        {
            return $this->errorResponse('This user is not playing in this game.', 403);
        }
    }

    public function confirm($game_id, $user_id)
    {
        $game = Game::find($game_id);
        $user = User::find($user_id);

        
    }

    public function remove($game_id, $user_id)
    {
        $game = Game::find($game_id);
        $user = User::find($user_id);

        if($game->creator_id == $user->id)
            return $this->errorResponse('You cannot leave game without ending it.', 403);

        $game->users()->detach($user);
        
        event(new PlayerLeave($user));

        $user->in_game = null;
        $user->save();

        return $user;
    }
}