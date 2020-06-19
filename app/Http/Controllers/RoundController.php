<?php

namespace App\Http\Controllers;

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

    }
}
