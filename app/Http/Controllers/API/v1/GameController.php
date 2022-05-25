<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameQuestion;
use App\Models\PointLog;
use App\Models\Participant;
use App\Models\TournamentQuestion;
use Illuminate\Support\Facades\Auth;


class GameController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(Game::with('tournament')->with('gameQuestions')->with('user')->get());
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'tournamentId' => 'required',
            'visiblity' => 'required',
            ]);
        $game = new Game();
 
        $game->tournament_id = $request->tournamentId;
        $game->name = $request->name;
        $game->visiblity = $request->visiblity;
        $game->user_id = Auth::id();
 
        $game->save();
        if($game->id){
            $gp = new GamePlayer();
            $gp->user_id =  Auth::id();
            $gp->game_id = $game->id;
            $gp->save();
        }

        foreach($request->questions as $question_id => $is_selected){
            if($is_selected){
                $game_questions = new GameQuestion();
                $game_questions->game_id = $game->id;
                $game_questions->question_id = $question_id;
                $game_questions->points = 10;
                $game_questions->save();
            }
            
        }

        return $this->sendResponse($game);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->sendResponse(Game::find($id));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return 'delete';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function request(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required',
            'reciver_id' => 'required',
        ]);

        $game_request = new GameRequest();
        $game_request->game_id = $request->game_id;
        $game_request->reciver_id = $request->reciver_id;
        $game_request->sender_id = $request->sender_id;
        return $this->game_request($game);
    }

    public function getParticipant()
    {
        return $this->sendResponse(Participant::all());
    }

    public function gamePlayers($game_id)
    {
        $gamePlayers = GamePlayer::where('game_id', '=', $game_id)->with('user')->with(['pointLog' => function ($q) {
            $q->Days();
          }])->get();

        $users = [];
        $match_Days = [];
        foreach($gamePlayers as $gamePlayer ){
            $user['name'] = $gamePlayer->user->name;
            //print_r($gamePlayer->pointLog);
            foreach($gamePlayer->pointLog as $pointLog){
                $matchDay_name = str_replace(' ', '', $pointLog->matchDay->name);
                $user[$matchDay_name] = $pointLog->totalPoints;
                
                if (!in_array($matchDay_name, $match_Days)){
                    $match_Days[] = $matchDay_name; 
                }
            }
            array_push($users, $user);
        }
        return $this->sendResponse(['users' => $users, 'matchDays' => $match_Days]);
    }

    public function logs($game_id)
    {
        $pointLogs = PointLog::where('game_id', '=', $game_id)->with('user')->with('prediction.m')->with('prediction.m.score')->with('prediction.m.homeParticipant')->with('prediction.m.awayParticipant')->get();

        $logs = [];
        foreach($pointLogs as $pointLog){
            $log['name'] =  $pointLog->user->name;
            $log['prediction'] = $pointLog->prediction->homeParticipant .':'. $pointLog->prediction->awayParticipant;
            $log['score'] = $pointLog->prediction->m->score->home_participant .':'.  $pointLog->prediction->m->score->away_participant;
            $log['points'] = $pointLog->points;
            array_push($logs, $log);
        }
        return $this->sendResponse($logs);
    }
}
