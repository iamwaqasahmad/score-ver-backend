<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tournament;
use App\Models\User;

class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::pluck('id')[$this->faker->numberBetween(1,User::count()-1)],
            'tournament_id' =>  Tournament::pluck('id')[$this->faker->numberBetween(1,Tournament::count()-1)]
        ];
    }
}
