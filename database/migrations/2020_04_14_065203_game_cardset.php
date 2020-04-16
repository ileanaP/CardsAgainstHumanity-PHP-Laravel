<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GameCardset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('game_cardset'))
            Schema::drop('game_cardset');

        Schema::create('game_cardset', function (Blueprint $table) {
            $table->bigInteger('game_id')->unsigned();
            $table->bigInteger('cardset_id')->unsigned();

            $table->foreign('game_id')->references('id')->on('games');
            $table->foreign('cardset_id')->references('id')->on('cardsets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_cardset');
    }
}
