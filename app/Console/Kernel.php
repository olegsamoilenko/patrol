<?php

namespace App\Console;

use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('users:delete')->everyMinute();
//        $schedule->call(function () {
//            $date = Carbon::now()->subDay(3);
//            dump($date);
//            $users = User::where('is_activated', '=', 'Ні')->where('created_at', '<', $date)->get();
//            dump($users);
//            foreach ($users as $user) {
//                $user->delete();
//            }
//        })->everyMinute();
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
