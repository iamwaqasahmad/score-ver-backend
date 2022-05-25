<?php

namespace App\Http\Controllers\API\V1\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Score;
use Illuminate\Support\Facades\Auth;


class ScoreController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $raw_scores = Score::with('match')->with('match.homeParticipant')->with('match.awayParticipant')->get();
        $scores = [];
        foreach($raw_scores as $s){
            $score_obj['id'] = $s->id;
            $score_obj['dateTime'] = $s->match->date . ' '. $s->match->time;
            $score_obj['match'] = $s->match->homeParticipant->short_name .' VS '.$s->match->awayParticipant->short_name;
            $score_obj['score'] = $s->home_participant .':' .$s->away_participant;
            
            array_push($scores, $score_obj);
        }

        return $this->sendResponse($scores);
    }

    public function createScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_id' => 'required',
            'home_participant' => 'required',
            'away_participant' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $score = new Score();
        $score->match_id = $request->match_id;
        $score->home_participant = $request->home_participant;
        $score->away_participant = $request->away_participant;
        $score->save();
        
    }

    
    
}
