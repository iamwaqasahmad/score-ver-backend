<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MatchPrediction;
use App\Models\QuestionPrediction;

class PredictionController extends BaseController
{
    public function matchPredicte(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required',
            'match_id' => 'required',
            'homeParticipant' => 'required',
            'awayParticipant' => 'required',
        ]);
        $user_id =  Auth::id();
        $prediction = MatchPrediction::updateOrCreate(
            [
                'user_id' => $user_id,
                'game_id' => $request->game_id,
                'match_id' => $request->match_id
            ],
            [
                'homeParticipant' => $request->homeParticipant,
                'awayParticipant' => $request->awayParticipant,
            ]
        );
        return $this->sendResponse($prediction);
    }

    public function questionPredicte(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required',
            'question_id' => 'required',
            'prediction' => 'required',
        ]);
        $user_id =  Auth::id();
        $prediction =  QuestionPrediction::updateOrCreate(
            [
                'user_id' => $user_id,
                'game_id' => $request->game_id,
                'question_id' => $request->question_id
            ],
            [
                'prediction' => $request->prediction,
            ]
        );
        return $this->sendResponse($prediction);
    }
}
