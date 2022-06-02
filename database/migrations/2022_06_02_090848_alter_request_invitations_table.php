<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRequestInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE requests_invitation MODIFY status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE requests_invitation MODIFY status ENUM('pending', 'accepted') DEFAULT 'pending'");
    }
}
