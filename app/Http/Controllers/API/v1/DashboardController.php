<?php

namespace App\Http\Controllers\API\v1;


use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Season;
use App\Models\GamePlayer;
use App\Models\MatchPrediction;
use Illuminate\Support\Facades\Auth;
use DB;
use NumConvert;
class DashboardController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id =  Auth::id();
        $gamePlayers = GamePlayer::where('user_id', $user_id)->with('game')->get();
        $predictions = MatchPrediction::where('user_id', $user_id)->get();
        $games = [
            'totalGames' => $gamePlayers->count(),
            'totalPredictions' => $predictions->count(),
            'games' => []
        ];
        foreach($gamePlayers as $gp){
            $p = GamePlayer::select(
                DB::raw('ROW_NUMBER() OVER(ORDER BY points DESC) AS row_num, game_id, user_id'))->where('game_id', $gp->game_id)->where('user_id', $user_id)->first();

            $game['game_id'] = $gp->game_id;
            $game['name'] = $gp->game->name;
            $game['points'] = $gp->points;
            $game['position'] = NumConvert::numberOrdinal($p->row_num);
            array_push($games['games'], $game);
        }
        return $this->sendResponse($games);
    }
}
