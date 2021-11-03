<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tournament;
use App\Models\User;
class TournamentQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tournament_id' => Tournament::pluck('id')[$this->faker->numberBetween(1,Tournament::count()-1)],
            'question' => $this->faker->sentence(),
            'answer' => $this->faker->word(),
        ];
    }
}
