<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\lib\Statorium;
use App\Models\Season;
use App\Models\Matches as MatchesModel;
use App\Models\MatchDay;
use App\Models\Score;

class Matches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Statorium $statorium)
    {
        parent::__construct();
        $this->statorium = $statorium;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $seasons = Season::all();
        foreach($seasons as $season){
            $matchDays = $this->statorium->getMatches($season->id);
            foreach($matchDays as $matchday){
                
                MatchDay::updateOrCreate(
                    [
                        'id' => $matchday->matchdayID,
                        'season_id' => $season->id
                    ],
                    [
                        'name' => $matchday->matchdayName,
                        'playoff' => $matchday->matchdayPlayoff,
                        'type' => $matchday->matchdayType,
                        'start' => $matchday->matchdayStart,
                        'end' => $matchday->matchdayEnd,
                        
                    ]
                );

               foreach($matchday->matches as $match){
                MatchesModel::updateOrCreate(
                    [
                        'id' => $match->matchID,
                        'season_id' => $season->id
                    ],
                    [
                        'home_participant_id' => $match->homeParticipant->participantID,
                        'away_participant_id' => $match->awayParticipant->participantID,
                        'status' => $match->matchStatus->statusID,
                        'date' => $match->matchDate,
                        'time' => $match->matchTime,
                        'matchDay_id' => $matchday->matchdayID
                    ]
                );

                //update score table if match is a played match
                if($match->matchStatus->statusID == 1 ){
                    Score::updateOrCreate(
                        [
                            'match_id' => $match->matchID
                        ],
                        [
                            'home_participant' => $match->homeParticipant->score,
                            'away_participant' => $match->awayParticipant->score,
                        ]
                    );
                }

               }
            }
        }
    }
}
