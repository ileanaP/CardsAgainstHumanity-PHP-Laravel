<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request; 
use App\Http\Controllers\ApiController; 
use App\User;
use App\Round;
use App\Card;
use Illuminate\Support\Facades\DB;

class UserRoundController extends ApiController
{
    public function index(User $user)
    {
        $cards = DB::table('user_round')->where('user_id', $user->id)
                        ->get();//->pluck('cards');
        return $cards;
    }

    public function show(User $user, Round $round)
    {
        $cards = DB::table('user_round')
                    ->where('user_id', $user->id)
                    ->where('round_id', $round->id)->get()->pluck('cards');

        $cards = Card::whereIn('id', json_decode($cards[0]))->get();

        return $cards;
    }
}
