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
            'tournament_id' => 'required',
            'question' => 'required'
        ]);
        $question = new TournamentQuestion();
 
        $question->tournament_id = $request->tournament_id;
        $question->question = $request->question;
        $question->answer = '';
 
        $question->save();

        return $this->sendResponse($questions);
    }

    public function createAnswer(Request $request, $id)
    {
        $validated = $request->validate([
            'answer' => 'required'
        ]);
        $question = TournamentQuestion::find($id);
        $question->answer = $request->answer;
 
        $question->save();

        return $this->sendResponse($questions);
    }

    
    
}
