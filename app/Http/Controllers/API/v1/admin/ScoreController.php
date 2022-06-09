<?php

namespace App\Http\Controllers\API\V1\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Score;
use Illuminate\Support\Facades\Auth;
use Validator;

class ScoreController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $raw_scores = Score::with('match')->with('match.homeParticipant')->with('match.awayParticipant')->with('match.season')->get();
        $scores = [];
        foreach($raw_scores as $s){
            $score_obj['id'] = $s->id;
            $score_obj['dateTime'] = $s->match->date . ' '. $s->match->time;
            $score_obj['match'] = $s->match->homeParticipant->short_name .' VS '.$s->match->awayParticipant->short_name;
            $score_obj['score'] = $s->home_participant .':' .$s->away_participant;
            $score_obj['season'] = $s->match->season->full_name;
            array_push($scores, $score_obj);
        }

        return $this->sendResponse($scores);
    }

    public function createScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_id' => 'required',
            'home_participant' => 'required | numeric',
            'away_participant' => 'required | numeric',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $score = Score::updateOrCreate(
            [
                'match_id' => $request->match_id
            ],
            [
                'home_participant' => $request->home_participant,
                'away_participant' => $request->away_participant
            ]
        );

         return $this->sendResponse($score);
    }

    
    
}
