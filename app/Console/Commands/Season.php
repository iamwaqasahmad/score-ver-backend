<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\lib\Statorium;
use App\Models\League;
use App\Models\Season as SeasonModel;

class Season extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:seasons';

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
        $leagues = League::all();
        foreach($leagues as $league){
            $seasons = $this->statorium->getSeasons($league->id);
            foreach($seasons as $season){
                SeasonModel::updateOrCreate(
                    [
                        'id' => $season->seasonID,
                        'league_id' => $league->id
                    ],
                    [
                        'name' => $season->seasonName,
                        'full_name' =>  $league->name.' '.$season->seasonName,
                    ]
                );
            }
        }
    }
}
