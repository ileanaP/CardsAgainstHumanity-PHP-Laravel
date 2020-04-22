<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\User;
use App\Game;
use App\Card;
use App\Cardset;
use App\Round;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Game::truncate();
/*        Card::truncate();
        Cardset::truncate();
        Round::truncate();
        DB::table('game_user')->truncate();
        DB::table('card_cardset')->truncate();
        DB::table('game_cardset')->truncate();
*/
        $usrQtty = 1000;
        $gmsQtty = 20;
        
        factory(User::class, $usrQtty)->create();
        factory(Game::class, $gmsQtty)->create();

  /*      $filename=storage_path("dbseeder.json");
        
        $content = json_decode(file($filename)[0]);

        $cardsets = collect($content->cardSetTableElems)->map(function($el) {

            $props = array(
                            "id" => $el[0], 
                            "name" => $el[1]
                          );

            Cardset::create($props);

        });

        $cards = collect($content->cardTableElems)->map(function($el) {

            $props = array(
                            "id"   => $el[0],
                            "type" => $el[1], 
                            "text" => $el[2],
                            "pick" => $el[3]
                          );

            Card::create($props);
        
        });

        $cardsetCards = collect($content->cardSetCards)->map(function($el) {

            Cardset::find($el[0])->cards()->attach($el[1]);
        
        });
*/

    }
}
