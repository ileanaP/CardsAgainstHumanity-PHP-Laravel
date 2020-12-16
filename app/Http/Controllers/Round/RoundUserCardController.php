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
        $currBlackCardNr = intval($request['currBlackCardNr']);

        //$cards = json_decode("[2411,703]");
        $cards = Card::whereIn('id', $cards)->get();

        $currCards = DB::table('round_cards')
            ->where('user_id', $user->id)
            ->where('round_id', $round->id)
            ->get()
            ->pluck('card_id')
            ->all();

        $alreadySent = false;

        if(count($currCards) > 0)
        {
            $cards = Card::whereIn('id', $currCards)->get();

            $alreadySent = true;
        }
        else
        {
            foreach($cards as $card)
            {
                
                DB::table('round_cards')
                    ->insert(['user_id'       => $user->id,
                              'round_id'      => $round->id,
                              'card_id'       => $card->id,
                              'currBlackCard' => $currBlackCardNr]);
            }
        }

        event(new UserSentCard($user, $cards, $alreadySent));

        return 0;
    }

    public function index(Round $round, User $user)
    {
        return "stiff";
    }
}
