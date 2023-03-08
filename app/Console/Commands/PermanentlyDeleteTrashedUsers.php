<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PermanentlyDeleteTrashedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trashedUsers:permanentlyDelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete trashed users';

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
        $date = Carbon::now()->subDay(30);

        $users = User::onlyTrashed()->where('deleted_at', '<', $date)->get();

        foreach ($users as $user) {
            $user->forceDelete();
            $user->roles()->detach();
        }
    }
}
