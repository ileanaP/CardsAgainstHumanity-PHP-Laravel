<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CardCardsetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('card_cardset'))
            Schema::drop('card_cardset');

        Schema::create('card_cardset', function (Blueprint $table) {
            $table->bigInteger('card_id')->unsigned();
            $table->bigInteger('cardset_id')->unsigned();

            $table->foreign('card_id')->references('id')->on('cards');
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
        Schema::dropIfExists('card_cardset');
    }
}
