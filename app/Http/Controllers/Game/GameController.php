<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;

class GameController extends ApiController
{
    public function index()
    {
        $games = Game::all();
        return $this->showAll($games);
    }
}
