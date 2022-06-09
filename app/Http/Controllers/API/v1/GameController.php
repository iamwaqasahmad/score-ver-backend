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
use App\Models\RequestInvitation;
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
        $user_id =  Auth::id();
        $user_games = GamePlayer::where('user_id', '=', $user_id)->pluck('game_id')->toArray();
        $games = Game::whereIn('id', $user_games)->orWhere('visiblity', '=', 'public')->with('tournament')->with('gameQuestions')->with('user')->get();
        return $this->sendResponse($games);
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
        $game->visiblity = $request->visiblity ? 'public' : 'private';
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
        $user_id =  Auth::id();
        $game = Game::with('season')->with('user')->get()->find($id);
        $can_i_predict = GamePlayer::where('user_id', '=',$user_id)->where('game_id','=', $id)->count() >= 1 ? true:false;
        return $this->sendResponse([
            'game' => $game,
            'can_i_predict' => $can_i_predict,
            'user_id' => $user_id
        ]);
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
        $match_Days = ['T'];
        foreach($gamePlayers as $gamePlayer ){
            $user['Name'] = $gamePlayer->user->name;
            //print_r($gamePlayer->pointLog);
            $user['T'] = 0;
            foreach($gamePlayer->pointLog as $pointLog){
                $plmdn = str_replace(' ', '', $pointLog->matchDay->name);
                $matchDay_name = substr($plmdn, 0, 1);
                $matchDay_name .= substr($plmdn, -1);
                $user[$matchDay_name] = $pointLog->totalPoints;
                $user['T'] += $pointLog->totalPoints;
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
            $log['Name'] =  $pointLog->user->name;
            $log['Match'] =  $pointLog->prediction->m->homeParticipant->short_name. ':'.$pointLog->prediction->m->awayParticipant->short_name;
            $log['Prediction'] = $pointLog->prediction->homeParticipant .':'. $pointLog->prediction->awayParticipant;
            $log['Score'] = $pointLog->prediction->m->score->home_participant .':'.  $pointLog->prediction->m->score->away_participant;
            $log['Points'] = $pointLog->points;
            array_push($logs, $log);
        }
        return $this->sendResponse($logs);
    }

    public function getGameRequestInvitation($game_id)
    {
        $request_nvitation = RequestInvitation::where('game_id', '=', $game_id)->with('sender')->with('reciver')->get();
        return $this->sendResponse($request_nvitation);
    }
}
