<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\RequestInvitation;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;
use App\Models\GameQuestion;

class QuestionController extends BaseController
{

    public function questionsByTournamentId($tournament_id)
    {
        $tournament_id = $tournament_id;
        $questions = TournamentQuestion::where('tournament_id', '=', $tournament_id)->get();
        return $this->sendResponse($questions);
    }

    public function matchesByGameId($game_id)
    {
        $game = Game::find($game_id);
        $tournament_id = $game->tournament_id;

        $data = [
            'user_id' => Auth::id(),
            'game_id' => $game_id
        ];

        $questions = GameQuestion::where('game_id', '=', $game_id)->with('tournamentQuestion')->with(['questionPrediction' => function ($query) use ($data) {
            $query->where([
                ['game_id', $data['game_id']],
                ['user_id', $data['user_id']],
                ]); 
        }])->get();
        return $this->sendResponse($questions);
    }
}
