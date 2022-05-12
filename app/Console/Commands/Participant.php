<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\lib\Statorium;
use App\Models\Season;
use App\Models\League;
use App\Models\Participant as ParticipantModel;

class Participant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:participants';

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
       // print_r($this->statorium->getParticipants(95));
       //print_r($this->statorium->getTeam(679, 95));
       $seasons = Season::all();
        foreach($seasons as $season){
            $participants = $this->statorium->getParticipants($season->id);
            foreach($participants as $participant){
                $team = $this->statorium->getTeam($participant->participantID, $season->id);
                ParticipantModel::updateOrCreate(
                    [
                        'id' => $participant->participantID,
                    ],
                    [
                        'season_id' => $season->id,
                        'name' => $team->teamName,
                        'short_name' => $team->shortName,
                        'logo' => $team->logo
                    ]
                );
            }
        }
    }
}
