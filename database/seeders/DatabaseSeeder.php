<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
         \App\Models\Tournament::factory(5)->create();
         \App\Models\TournamentQuestion::factory(5)->create();
         \App\Models\Game::factory(5)->create();
         \App\Models\GameQuestion::factory(5)->create();
    }
}
