<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class dbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--type=1}'; // 1-manual 2-auto

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');

        $namebatabase = $database . '_' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = storage_path('app/backups/' . $namebatabase);

        // Ensure the backup directory exists
        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} > {$backupPath}";

        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            
            $type = $this->option('type');

            DB::table('backups')->insert([
                'path' => $backupPath,
                'size' => File::size($backupPath),
                'type' => $type,
                'created_at' => now(),
            ]);

            $this->info('Database backup was successful.');
            return Command::SUCCESS;
            
        } else {
            $this->error('Database backup failed.');
            return Command::FAILURE;
        }
    }
}
