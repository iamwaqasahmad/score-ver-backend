<?php

namespace App\Http\Controllers\API\v1;


use App\Http\Controllers\API\v1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\RequestInvitation;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches;

class MatchesController extends BaseController
{
    public function index()
    {
        $matches = Matches::all();
        return $this->sendResponse($matches);
    }

    public function matchesByTournamentId($tournament_id)
    {
        $tournament_id = $tournament_id;
        $matches = Matches::where('season_id', '=', $tournament_id)->upcoming()->with('homeParticipant')->with('awayParticipant')->get();
        return $this->sendResponse($matches);
    }

    public function matchesByGameId($game_id)
    {
        $game = Game::find($game_id);
        $tournament_id = $game->tournament_id;

        $data = [
            'user_id' => Auth::id(),
            'game_id' => $game_id
        ];

        $matches = Matches::where('season_id', '=', $tournament_id)->upcoming()->with('homeParticipant')->with('awayParticipant')->with(['matchPrediction' => function ($query) use ($data) {
            $query->where([
                ['game_id', $data['game_id']],
                ['user_id', $data['user_id']],
                ]); 
        }])->get();
        return $this->sendResponse($matches);
    }
}
