<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\lib\Statorium;
use App\Models\League as LeagueModel;

class League extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:leagues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import leagues for statorium api';

    protected $statorium;

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
        $leagues = $this->statorium->getLeagues();
        foreach($leagues as $league){
            LeagueModel::updateOrCreate(
                ['id' => $league->id],
                [
                    'name' => $league->name
                ]
            );
        }


    }
}
