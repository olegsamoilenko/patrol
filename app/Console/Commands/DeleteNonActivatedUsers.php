<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteNonActivatedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete non-activated user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now()->subDay(3);

        $users = User::where('is_activated', '=', 'Ні')->where('created_at', '<', $date)->get();

        foreach ($users as $user) {
            $user->delete();
        }
        return Command::SUCCESS;
    }
}
