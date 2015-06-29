<?php

namespace DriveNowChecker\Console\Commands;

use DriveNowChecker\Checkers\Checker;
use DriveNowChecker\Watcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ScheduleWatchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watchers:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule the watchers.';

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
     * @return mixed
     */
    public function handle()
    {
        $checker = new Checker();

        foreach (Watcher::with('user')->where('on', true)->get() as $watcher) {
            echo "watcher: " . ($checker->check($watcher) ? 'yes' : 'no') . "\n";
        }

        echo "done\n";
    }
}
