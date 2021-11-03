<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tournament;
use App\Models\TournamentQuestion;
use App\Models\Game;

class GameQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'game_id' => Game::pluck('id')[$this->faker->numberBetween(1,Game::count()-1)],
            'question_id' =>  TournamentQuestion::pluck('id')[$this->faker->numberBetween(1,TournamentQuestion::count()-1)],
            'points' => $this->faker->randomDigit()
        ];
    }
}
