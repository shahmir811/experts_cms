<?php

namespace App\Console\Commands;

use Log;
use Artisan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BackupCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take regular backup after two hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Log::info("Cron is working fine!");
        
        // start the backup process
        Artisan::call('backup:run',['--only-db' => true]);
        $output = Artisan::output();

        // log the results
        $timestamp = Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString();
        Log::info("Backup started at: " . $timestamp);
        Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n" . $output);

        return Command::SUCCESS;
    }
}
