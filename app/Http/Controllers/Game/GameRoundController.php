<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;
use App\Round;
use App\Card;
use Illuminate\Support\Facades\DB;

class GameRoundController extends ApiController
{
    public function index(Game $game)
    {
        $rounds = Round::where('game_id', $game->id)->get();

        return $this->showAll($rounds);
    }

    public function show(Game $game, Round $round)
    {
        return $this->showOne($round);
    }

    public function store(Game $game)
    {
        $round = $this->roundInProgress($game->id);

        if(count($round))
        {
            $round = $round[0];
        }
        else
        {
            $cards = $this->fetchCards($game->id);

            $cardss = DB::table('cardsets')->get()->pluck('id');


            return $cardss;

            $round = $this->saveRound($game->id, $cards['black']);

            $players = $this->getGamePlayers($game->id);

            $playersCount = count($players);

            $playersCards = array();

            for($i=0; $i < $playersCount; $i++)
                $playersCards[$i] = array();

            $j = 0;
            foreach($cards['white'] as $card)
            {
                $playersCards[$j][] = $card;
                $j++;
                $j %= $playersCount;
            }

            $roundCards = array();

            for($i=0; $i < $playersCount; $i++)
            {
                $roundCards[$players[$i]] = $playersCards[$i];
            }

            foreach($roundCards as $player => $cards)
            {
                DB::table('user_round')->insert(
                    ['user_id' => $player,
                    'round_id' => $round->id,
                    'cards' => json_encode($cards)]
                );
            }
        }

        return $this->showOne($round);
    }

    public function roundInProgress($gameId)
    {
        $round = Round::where('game_id', $gameId)
                        ->where('winner_id', null)->get();

        return $round;
    }

    public function fetchCards($gameId)
    {
        $cardsets = DB::table('game_cardset')
        ->select('cardset_id')->where('game_id', '=', $gameId)->get()
        ->pluck('cardset_id');

        $cards = DB::table('card_cardset')
            ->select('card_id')->whereIn('cardset_id', $cardsets)->get()
            ->pluck('card_id');

        

        $cards = Card::whereIn('id', $cards)
                ->select('id', 'type')->get()
                ->shuffle();

        $whiteCards = $cards->filter(function ($item) {
        return $item->type == 'white';
        })->values();

        $blackCards = $cards->diff($whiteCards);

        return array('white' => $whiteCards->pluck('id'), 'black' => $blackCards->pluck('id'));
    }

    public function saveRound($gameId, $cards)
    {
        $round = new Round();

        $round->game_id = $gameId;
        $round->card_data = json_encode($cards);

        $round->save();

        return $round;
    }

    public function getGamePlayers($gameId)
    {
        return DB::table('game_user')->where('game_id', '=', $gameId)->get()->pluck('user_id');
    }
}
