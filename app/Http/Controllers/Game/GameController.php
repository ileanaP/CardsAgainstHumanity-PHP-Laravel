<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;
use App\Card;
use App\Round;
use Illuminate\Support\Facades\DB;
use App\Events\GameEnded;


class GameController extends ApiController
{
    public function index()
    {
        $games = Game::with('creator', 'winner')->get();
        return $this->showAll($games);
    }

    public function show(Game $game)
    {
        return $this->showOne($game);
    }

    public function destroy(Game $game)
    {
        
        $users = DB::table('game_user')->where('game_id', $game->id)
                    ->get();

        foreach($users as $user)
        {
            $user->in_game = null;
            $user->save();
        }
        
        DB::table('game_user')->where('game_id', $game->id)->delete();

        Round::where('game_id', $game->id)->delete();
        $game->delete();

        event(new GameEnded($game));

        return $this->showOne($game);
    }
}
