<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;
use App\Round;
use App\Card;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Events\StartRound;
use App\Events\RoundCards;

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
            
            $userCards = DB::table('user_round')->where('round_id', $round->id)->get();

            $roundCards = array();

            foreach($userCards as $userCard)
            {
                $roundCards[$userCard->user_id] = json_decode($userCard->cards);
            }
        }
        else
        {
            $cards = $this->fetchCards($game->id);

            $players = $this->getGamePlayers($game->id);

            $round = $this->saveRound($game->id, $cards['black'], $players);

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

        event(new StartRound($game, $round));
        event(new RoundCards($game, $roundCards));

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
        $cardsets = Game::find($gameId)->cardsets;

        $cards = DB::table('card_cardset')
            ->select('card_id')->whereIn('cardset_id', json_decode($cardsets))->get()
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

    public function saveRound($gameId, $cards, $players)
    {
        $round = new Round();

        $round->game_id = $gameId;
        $round->card_data = json_encode($cards);
        $round->tsars = json_encode($players);

        $round->save();

        return $round;
    }

    public function getGamePlayers($gameId)
    {        
        $users = Game::find($gameId)->users()->get()->pluck('id');

        return $users;
    }
}
