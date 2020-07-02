<?php

namespace App\Http\Controllers\Round;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Game;
use App\Card;
use App\Round;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Events\UserSentCard;


class RoundUserCardController extends ApiController
{

    public function store(Request $request, Round $round, User $user)
    {
        $cards = json_decode($request['cards']);

        return $cards;

        foreach($cards as $card)
        {
            DB::table('round_cards')
                ->save(['user_id' => $user->id,
                    'game_id' => $game->id,
                    'card_id' => $card]);
        }

        $cards = Card::whereIn('id', $cards);

        event(new UserSentCard($user, $cards));
        
        return 0;
    }

    public function index(Round $round, User $user)
    {
        return "stiff";
    }
}
