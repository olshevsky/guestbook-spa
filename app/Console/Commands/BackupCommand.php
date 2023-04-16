<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now()->format('Y-m-d_H-i-s');
        $fileName = 'backup_'.$now.'.sql';
        $backupPath = storage_path('app/backups/'.$fileName);
        $result = exec('mysqldump --user='.env('DB_USERNAME').' --password='.env('DB_PASSWORD').' --host='.env('DB_HOST').' '.env('DB_DATABASE').' > '.$backupPath);

        if($result)
            $this->info('Database backup saved to '.$backupPath);
        else
            $this->info('Something went wrong. Check if  '.storage_path('app/backups/').' folder exist and is writable  ');
    }
}
