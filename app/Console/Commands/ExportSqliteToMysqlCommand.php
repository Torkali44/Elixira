<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportSqliteToMysqlCommand extends Command
{
    protected $signature = 'db:export-mysql {database=elixdlmq_elixira_db : Target MySQL database name}';

    protected $description = 'Export local SQLite database to MySQL SQL files for phpMyAdmin import';

    public function handle(): int
    {
        $database = (string) $this->argument('database');
        $script = base_path('database/scripts/export_sqlite_to_mysql.php');

        if (! file_exists(database_path('database.sqlite'))) {
            $this->error('SQLite file not found at database/database.sqlite');

            return self::FAILURE;
        }

        passthru(PHP_BINARY.' '.escapeshellarg($script).' '.escapeshellarg($database), $exitCode);

        if ($exitCode !== 0) {
            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Files ready:');
        $this->line('  • database/exports/'.$database.'.sql (full import with CREATE DATABASE)');
        $this->line('  • database/exports/'.$database.'_cpanel.sql (use on Namecheap/cPanel after selecting DB)');

        return self::SUCCESS;
    }
}
