<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DemoService;

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
    public function handle(DemoService $demoService)
    {
        $demoService->cleanUpDemoUserData();
    }
}
