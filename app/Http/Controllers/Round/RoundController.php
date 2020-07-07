<?php

namespace App\Http\Controllers\Round;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;
use App\Card;
use App\Round;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Events\GameEnded;


class RoundController extends ApiController
{

    public function index()
    {
        $rounds = Round::all();

        return $rounds;
    }

    public function store(Request $request)
    {
        return $request->apa;
    }

    public function show(Round $round)
    {

    }

    public function destroy(Round $round)
    {
        DB::table('user_round')->where('round_id', $round->id)->delete();
        DB::table('round_cards')->where('round_id', $round->id)->delete();

        $round->delete();
    }
}
