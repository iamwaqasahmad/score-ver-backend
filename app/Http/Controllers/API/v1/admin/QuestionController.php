<?php

namespace App\Http\Controllers\API\V1\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\TournamentQuestion;
use Illuminate\Support\Facades\Auth;


class QuestionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $raw_questions = TournamentQuestion::with('season')->get();
        $questions = [];
        foreach($raw_questions as $q){
            $question_obj['id'] = $q->id;
            $question_obj['season'] = $q->season->full_name;
            $question_obj['question'] = $q->question;
            $question_obj['answer'] = $q->answer;

            array_push($questions, $question_obj);
        }

        return $this->sendResponse($questions);
    }

    public function show($questionId)
    {
        $raw_questions = TournamentQuestion::find($questionId);
        return $this->sendResponse($raw_questions);
    }


    public function createQuestion(Request $request)
    {
        $validated = $request->validate([
            'seasonId' => 'required',
            'question' => 'required',
            'points' => 'required'
        ]);
        $question = new TournamentQuestion();
 
        $question->tournament_id = $request->seasonId;
        $question->question = $request->question;
        $question->points = $request->points;
        $question->save();

        return $this->sendResponse($question);
    }

    public function updateQuestion(Request $request, $id)
    {
        $validated = $request->validate([
            'seasonId' => 'required',
            'question' => 'required',
            'points' => 'required'
        ]);
        $question = TournamentQuestion::find($id);
        if(isset($request->answer) && $request->answer != '')
            $question->answer = $request->answer;

        $question->tournament_id = $request->seasonId;
        $question->question = $request->question;
        $question->points = $request->points;
        $question->save();

        return $this->sendResponse($question);
    }

    
    
}
