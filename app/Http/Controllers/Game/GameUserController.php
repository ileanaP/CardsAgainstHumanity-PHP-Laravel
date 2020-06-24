<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;
use App\User;
use App\Http\Controllers\Game\GameController;
use Illuminate\Support\Facades\DB;
use App\Events\PlayerEnter;
use App\Events\PlayerLeave;
use App\Events\PlayerConfirmed;

class GameUserController extends ApiController
{
    public function index(Game $game)
    {

        $users = $game->users()->get();
        
        $users = $users->map(function($stuff){
            $stuff->confirmed = DB::table('game_user')
                                    ->where('user_id', $stuff->id)->get()
                                    ->pluck('confirmed')[0];

            return $stuff;
        });

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

            $user->save();
            $game->users()->save($user);

            $user->confirmed = DB::table('game_user')->where('user_id', $user->id)->get()
                                    ->pluck('confirmed')[0];

            event(new PlayerEnter($user));
        }

        if($user->in_game != null)
        {
            $user->confirmed = DB::table('game_user')->where('user_id', $user->id)->get()
                                    ->pluck('confirmed')[0];
            
            return $this->showOne($user);
        }
        else
        {
            return $this->errorResponse('This user is not playing in this game.', 403);
        }
    }

    public function confirm($game_id, $user_id)
    {
        DB::table('game_user')
            ->where('game_id', intval($game_id))
            ->where('user_id', intval($user_id))
            ->update(['confirmed' => 1]);

        event(new PlayerConfirmed($game_id, $user_id));

        return;
    }

    public function remove($game_id, $user_id)
    {
        $game = Game::find($game_id);
        $user = User::find($user_id);

        if($game == null || $user == null)
            return $this->errorResponse('Data not found', 404);

        if($game->creator_id == $user->id)
        {
            
            if(count($game->users()->get()) == 1)
            {
                $gameCtrl = new GameController;
                $gameCtrl->destroy($game);
            }
            else
            {
                return $this->errorResponse('You cannot leave game without ending it.', 403);
            }
        }
        else
        {
            $game->users()->detach($user);
        
            event(new PlayerLeave($user));

            $user->in_game = null;
            $user->save();
        }

        return $user;
    }
}