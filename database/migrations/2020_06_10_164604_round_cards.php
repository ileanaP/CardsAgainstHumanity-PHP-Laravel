<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RoundCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('round_cards'))
            Schema::drop('round_cards');

        Schema::create('round_cards', function (Blueprint $table) {
            $table->bigInteger('round_id')->unsigned();
            $table->bigInteger('card_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_round');
    }
}
