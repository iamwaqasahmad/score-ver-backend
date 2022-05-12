<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchPredictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('game_id');
            $table->foreignId('match_id');
            $table->integer('homeParticipant');
            $table->integer('awayParticipant');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_predictions');
    }
}
