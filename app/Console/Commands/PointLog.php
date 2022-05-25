<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Score;
use App\Models\MatchPrediction;
use App\Models\PointLog as PointLogModel;
use App\lib\PointCalculator;

class PointLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:pointlog';

    /**
     * The console command description.
     *                                 
     * @var string
     */
    protected $description = 'Logs all the points';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $scores = Score::all();
        foreach($scores as $score){
            $match_predictions = MatchPrediction::where(
                    'match_id', '=', $score->match_id,
            )->with('Matches')->get();

            foreach($match_predictions as $mp){
                
                $real_score = [
                    'homeParticipant' => $score->home_participant,
                    'awayParticipant' => $score->away_participant
                ];
                $predicted_score = [
                    'homeParticipant' => $mp->homeParticipant,
                    'awayParticipant' => $mp->awayParticipant
                ];

                $point_calculator = new PointCalculator($real_score, $predicted_score);
                $points = $point_calculator->points;

                $pl = PointLogModel::firstOrNew(
                    ['ref_id' =>  $mp->id]
                );
                $pl->user_id = $mp->user_id;
                $pl->game_id =  $mp->game_id;
                $pl->matchDay_id = $mp->Matches->matchDay_id;
                $pl->type = 'match';
                $pl->points = $points;
                $pl->details = 'Prediction bonus';
                $pl->save();
            }
        }
    }
}
