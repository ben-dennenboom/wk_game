<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupWC22Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wk:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(3);

        $bar->start();
        Artisan::call('migrate:fresh');
        $bar->advance();
        Artisan::call('db:seed');
        $bar->advance();
        Artisan::call('wk:scrape');
        $bar->advance();

        return 0;
    }
}
