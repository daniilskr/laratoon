<?php

namespace App\Console\Commands;

use App\Services\Demo\DemoDataCleaner;
use Illuminate\Console\Command;

class CleanUpDemoUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up demo users data';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(DemoDataCleaner $cleaner)
    {
        $cleaner->cleanUpDemoUserData();
    }
}
