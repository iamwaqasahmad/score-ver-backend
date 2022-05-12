<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsInvitationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests_invitation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id');
            $table->string('reciver_id');
            $table->foreignId('game_id');
            $table->enum('type', ['request', 'invite_via_email', 'invite_via_id'])->default('request');
            $table->enum('status', ['Pending', 'accepted'])->default('Pending');
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
        Schema::dropIfExists('requests_invitation');
    }
}
