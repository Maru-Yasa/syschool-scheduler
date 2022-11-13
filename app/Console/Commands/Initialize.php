<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Initialize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize scheduler pro';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Log::info('Welcome to Scheduler Pro');



        return 0;
    }
}
