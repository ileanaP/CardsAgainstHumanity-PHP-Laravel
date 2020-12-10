<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;
use App\Card;
use App\Round;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Events\GameEnded;
use App\Events\PingPlayersToConfirm;


class GameController extends ApiController
{
    public function index()
    {
        $games = Game::with('creator', 'winner')->get();
        return $this->showAll($games);
    }

    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'password' => 'required',
            'creator_id' => 'required',
            'cardsets' => 'required'
        ];

        Validator::validate($request->all(), $rules);

        $user = User::find($request['creator_id']);

        if($user->in_game != null)
            return $this->errorResponse('You are already in a game', 403);
        
        $request['password'] = Hash::make($request['password']);
        $game = Game::create($request->all());
        
        $user->in_game = $game->id;
        $user->save();

        $game->users()->attach($user->id);

        return $game;
    }

    public function show(Game $game)
    {
        return $this->showOne($game);
    }

    public function destroy(Game $game)
    {        
        $users = DB::table('game_user')->where('game_id', $game->id)
                    ->get('user_id')
                    ->pluck('user_id');

        foreach($users as $user)
        {
            $user = User::find($user);

            $user->in_game = null;
            $user->save();
        }
        
        DB::table('game_user')->where('game_id', $game->id)->delete();

        $rounds = Round::where('game_id', $game->id)->get()->pluck('id');

        DB::table('user_round')->whereIn('round_id', $rounds)->delete();

        Round::where('game_id', $game->id)->delete();
        
        User::where('in_game', $game->id)->update(['in_game' => null]);
        
        $game->delete();

        event(new GameEnded($game));

        return $this->showOne($game);
    }

    public function pingPlayersToConfirm($gameId)
    {
        $game = Game::find($gameId);

        event(new PingPlayersToConfirm($game));

        return 0;
    }
}
