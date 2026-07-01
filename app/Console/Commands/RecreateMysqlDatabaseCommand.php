<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;

class RecreateMysqlDatabaseCommand extends Command
{
    protected $signature = 'db:recreate-mysql {--force : Recreate without confirmation}';

    protected $description = 'Drop and recreate the configured MySQL database (use after fixing orphaned tablespaces)';

    public function handle(): int
    {
        if (config('database.default') !== 'mysql') {
            $this->error('Current DB_CONNECTION is not mysql.');

            return self::FAILURE;
        }

        $database = (string) config('database.connections.mysql.database');

        if (! $this->option('force') && ! $this->confirm("Drop and recreate database `{$database}`?")) {
            return self::SUCCESS;
        }

        $host = (string) config('database.connections.mysql.host');
        $port = (string) config('database.connections.mysql.port');
        $username = (string) config('database.connections.mysql.username');
        $password = (string) config('database.connections.mysql.password');

        $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $this->call('db:fix-mysql-orphans', ['--force' => true]);

        $pdo->exec('DROP DATABASE IF EXISTS `'.$database.'`');
        $pdo->exec('CREATE DATABASE `'.$database.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

        $this->info("Database `{$database}` recreated.");

        return self::SUCCESS;
    }
}
