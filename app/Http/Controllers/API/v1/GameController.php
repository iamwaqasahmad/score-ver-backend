<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameQuestion;
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
        $game = Game::with('tournament')->with('gameQuestions')->with('user')->find($id);

        foreach($game->gameQuestions as $i=>$q){
            $tournamen_question = TournamentQuestion::find($q->question_id);
            $game->gameQuestions[$i]['id'] = $i;
            $game->gameQuestions[$i]['text'] = $tournamen_question->question;
        }


        return $this->sendResponse($game);
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
}
