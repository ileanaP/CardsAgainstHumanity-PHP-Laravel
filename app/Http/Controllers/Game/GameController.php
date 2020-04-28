<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;
use App\Card;
use Illuminate\Support\Facades\DB;

class GameController extends ApiController
{
    public function index()
    {
        $games = Game::with('creator', 'winner')->get();
        return $this->showAll($games);
    }

    public function show(Game $game)
    {
        return $this->initRound($game);
    }

    public function initRound($game)
    {
        $cardsets = DB::table('game_cardset')
        ->select('cardset_id')->where('game_id', '=', $game->id)->get()
        ->pluck('cardset_id');

        $cards = DB::table('card_cardset')
            ->select('card_id')->whereIn('cardset_id', $cardsets)->get()
            ->pluck('card_id');

        $cards = Card::whereIn('id', $cards)
                ->select('id', 'text', 'type', 'pick')->get()
                ->shuffle();

        $whiteCards = $cards->filter(function ($item) {
        return $item->type == 'white';
        })->values();

        $blackCards = $cards->diff($whiteCards);

        $players = DB::table('game_user')->where('game_id', '=', $game->id)->get()->pluck('user_id');

        $playersCount = count($players);

        $playersCards = array();

        for($i=0; $i < $playersCount; $i++)
        $playersCards[$i] = array();

        $j = 0;
        foreach($whiteCards as $card)
        {
            $playersCards[$j][] = $card;
            $j++;
            $j %= $playersCount;
        }

        $round['blackCards'] = $blackCards;
        $round['playerAndCards'] = array();

        for($i=0; $i < $playersCount; $i++)
        {
            $round['playerAndCards'][$players[$i]] = $playersCards[$i];
        }

        return json_encode($round);
    }
}
