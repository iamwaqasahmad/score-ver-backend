<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\V1\BaseController as BaseController;
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
}
