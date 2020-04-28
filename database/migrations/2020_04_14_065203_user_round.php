<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserRound extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('user_round'))
            Schema::drop('user_round');

        Schema::create('user_round', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('round_id')->unsigned();
            $table->longText('cards');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('round_id')->references('id')->on('rounds');
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
