<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;

class FixMysqlOrphanedTablesCommand extends Command
{
    protected $signature = 'db:fix-mysql-orphans {--force : Delete orphaned .ibd files without confirmation}';

    protected $description = 'Remove orphaned InnoDB tablespace files that block migrations (MySQL error 1813)';

    public function handle(): int
    {
        if (config('database.default') !== 'mysql') {
            $this->error('Current DB_CONNECTION is not mysql.');

            return self::FAILURE;
        }

        $database = (string) config('database.connections.mysql.database');
        $host = (string) config('database.connections.mysql.host');
        $port = (string) config('database.connections.mysql.port');
        $username = (string) config('database.connections.mysql.username');
        $password = (string) config('database.connections.mysql.password');

        $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $datadir = rtrim(str_replace('/', DIRECTORY_SEPARATOR, (string) $pdo->query('SELECT @@datadir')->fetchColumn()), DIRECTORY_SEPARATOR);
        $dbPath = $datadir.DIRECTORY_SEPARATOR.$database;

        if (! is_dir($dbPath)) {
            $this->info("Database folder not found: {$dbPath}");

            return self::SUCCESS;
        }

        $pdo->exec('USE `'.$database.'`');
        $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        $tableSet = array_flip($tables);

        $orphans = [];
        foreach (scandir($dbPath) ?: [] as $file) {
            if (! str_ends_with($file, '.ibd')) {
                continue;
            }

            $tableName = substr($file, 0, -4);
            if (! isset($tableSet[$tableName])) {
                $orphans[] = $file;
            }
        }

        if ($orphans === []) {
            $this->info('No orphaned .ibd files found.');

            return self::SUCCESS;
        }

        $this->warn('Found '.count($orphans).' orphaned tablespace file(s) in:');
        $this->line($dbPath);
        foreach ($orphans as $file) {
            $this->line("  • {$file}");
        }

        if (! $this->option('force') && ! $this->confirm('Delete these files?')) {
            return self::SUCCESS;
        }

        foreach ($orphans as $file) {
            unlink($dbPath.DIRECTORY_SEPARATOR.$file);
        }

        $this->info('Orphaned files removed. Run: php artisan migrate:fresh --seed');

        return self::SUCCESS;
    }
}
