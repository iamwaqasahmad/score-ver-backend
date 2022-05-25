<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPointLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('point_logs', function (Blueprint $table) {
            $table->foreignId('game_id');
            $table->foreignId('matchDay_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_logs', function (Blueprint $table) {
            $table->dropColumn('matchDay_id');
        });
    }
}
