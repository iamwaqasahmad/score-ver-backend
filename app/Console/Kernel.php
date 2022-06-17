<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\League::class,
        Commands\Season::class,
        Commands\Participant::class,
        Commands\Matches::class,
        Commands\PointLog::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('League:import')
                 ->everyThirtyMinutes();
        $schedule->command('Season:import')
                 ->everyThirtyMinutes();
        $schedule->command('Participant:import')
                 ->everyThirtyMinutes();
        $schedule->command('Matches:import')
                 ->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('PointLog:import')
                 ->everyThirtyMinutes()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
