<?php

namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;

protected function schedule(Schedule $schedule)
{
    $schedule->command('telescope:prune')->daily();
}
