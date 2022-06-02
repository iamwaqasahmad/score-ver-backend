<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Season;
use App\Models\TournamentQuestion;

class TournamentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(Season::with('tournamentQuestions')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($tournamentId)
    {
        return $this->sendResponse(Tournament::find($tournamentId));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function questions($tournamentId)
    {
        return $this->sendResponse(TournamentQuestion::select('id','tournament_id', 'question')->where('tournament_id', $tournamentId)->get());
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
            'name' => 'required|max:255',
            'code' => 'required|max:255',
        ]);
        $tournament = new Tournament();
 
        $tournament->name = $request->name;
        $tournament->code = $request->name;
 
        $tournament->save();

        return $tournament->name;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function questionStore(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ]);
        $tournament_question = new TournamentQuestion();
 
        $tournament_question->tournament_id = $request->tournament_id;
        $tournament_question->question = $request->question;
        $tournament_question->answer = $request->answer;
 
        $tournament_question->save();

        return $tournament_question->question;
    }

    
}
