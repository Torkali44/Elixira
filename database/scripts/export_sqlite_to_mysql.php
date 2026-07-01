<?php

declare(strict_types=1);

/**
 * Export local SQLite database to MySQL-compatible SQL for phpMyAdmin.
 *
 * Usage: php database/scripts/export_sqlite_to_mysql.php [database_name]
 */

$databaseName = $argv[1] ?? 'elixdlmq_elixira_db';
$sqlitePath = __DIR__.'/../database.sqlite';
$outputPath = __DIR__.'/../exports/'.$databaseName.'.sql';
$cpanelOutputPath = __DIR__.'/../exports/'.$databaseName.'_cpanel.sql';

if (! file_exists($sqlitePath)) {
    fwrite(STDERR, "SQLite file not found: {$sqlitePath}\n");
    exit(1);
}

if (! is_dir(dirname($outputPath))) {
    mkdir(dirname($outputPath), 0755, true);
}

$pdo = new PDO('sqlite:'.$sqlitePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name")
    ->fetchAll(PDO::FETCH_COLUMN);

$sql = [];
$sql[] = '-- Elixira MySQL export generated from SQLite';
$sql[] = '-- Generated: '.date('Y-m-d H:i:s');
$sql[] = '-- Target database: '.$databaseName;
$sql[] = '-- Import via phpMyAdmin: Import tab -> choose this file -> Go';
$sql[] = '';
$sql[] = 'SET NAMES utf8mb4;';
$sql[] = 'SET time_zone = "+00:00";';
$sql[] = 'SET FOREIGN_KEY_CHECKS = 0;';
$sql[] = 'SET UNIQUE_CHECKS = 0;';
$sql[] = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";';
$sql[] = '';
$sql[] = 'CREATE DATABASE IF NOT EXISTS `'.$databaseName.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;';
$sql[] = 'USE `'.$databaseName.'`;';
$sql[] = '';

foreach ($tables as $table) {
    $sql[] = 'DROP TABLE IF EXISTS `'.$table.'`;';
}

$sql[] = '';

foreach ($tables as $table) {
    $sql[] = buildCreateTable($pdo, $table);
    $sql[] = '';
}

foreach ($tables as $table) {
    $inserts = buildInserts($pdo, $table);
    if ($inserts !== []) {
        $sql[] = '-- Data for table `'.$table.'`';
        $sql = array_merge($sql, $inserts);
        $sql[] = '';
    }
}

$sql[] = 'SET FOREIGN_KEY_CHECKS = 1;';
$sql[] = 'SET UNIQUE_CHECKS = 1;';
$sql[] = '-- Import complete.';

file_put_contents($outputPath, implode(PHP_EOL, $sql));

$cpanelSql = array_filter($sql, static fn (string $line): bool => ! str_starts_with($line, 'CREATE DATABASE') && ! str_starts_with($line, 'USE `'));
array_unshift($cpanelSql, '-- cPanel/phpMyAdmin: select database `'.$databaseName.'` first, then import this file.');
file_put_contents($cpanelOutputPath, implode(PHP_EOL, $cpanelSql));

$sizeKb = round(filesize($outputPath) / 1024, 1);
echo "Exported {$databaseName} -> {$outputPath} ({$sizeKb} KB, ".count($tables).' tables)'.PHP_EOL;
echo "cPanel version -> {$cpanelOutputPath}".PHP_EOL;

function buildCreateTable(PDO $pdo, string $table): string
{
    $createSql = (string) $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name=".$pdo->quote($table))->fetchColumn();
    $columns = $pdo->query("PRAGMA table_info('{$table}')")->fetchAll(PDO::FETCH_ASSOC);
    $columnDefs = [];

    foreach ($columns as $column) {
        $columnDefs[] = '  '.buildColumnDefinition($column);
    }

    $foreignKeys = extractForeignKeys($table, $createSql);
    foreach ($foreignKeys as $foreignKey) {
        $columnDefs[] = '  '.$foreignKey;
    }

    $indexes = buildIndexes($pdo, $table, $columns);

    $parts = array_merge($columnDefs, $indexes);

    return "CREATE TABLE `{$table}` (\n".implode(",\n", $parts)."\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
}

function buildColumnDefinition(array $column): string
{
    $name = $column['name'];
    $type = strtolower((string) $column['type']);
    $notNull = (int) $column['notnull'] === 1;
    $default = $column['dflt_value'];
    $isPrimary = (int) $column['pk'] === 1;

    if ($isPrimary && str_contains($type, 'int')) {
        $mysqlType = 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT';
        $line = "`{$name}` {$mysqlType}";

        if ($name === 'id') {
            return $line.' PRIMARY KEY';
        }

        return $line;
    }

    $mysqlType = mapSqliteTypeToMysql($type, $name);

    $line = "`{$name}` {$mysqlType}";

    if ($notNull) {
        $line .= ' NOT NULL';
    } else {
        $line .= ' NULL';
    }

    if ($default !== null) {
        $line .= ' DEFAULT '.formatDefaultValue($default, $mysqlType);
    }

    return $line;
}

function mapSqliteTypeToMysql(string $type, string $columnName): string
{
    if (str_contains($type, 'tinyint')) {
        return 'TINYINT(1)';
    }

    if (str_contains($type, 'int')) {
        if (str_ends_with($columnName, '_id') || $columnName === 'batch') {
            return 'BIGINT UNSIGNED';
        }

        return 'INT';
    }

    if (str_contains($type, 'numeric') || str_contains($type, 'decimal') || str_contains($type, 'real') || str_contains($type, 'float') || str_contains($type, 'double')) {
        return 'DECIMAL(12,2)';
    }

    if (str_contains($type, 'datetime') || str_contains($type, 'timestamp')) {
        return 'DATETIME';
    }

    if (str_contains($type, 'date')) {
        return 'DATE';
    }

    if (str_contains($type, 'text') || str_contains($type, 'clob')) {
        if (in_array($columnName, ['cart_data', 'description', 'description_en', 'description_ar', 'long_description_en', 'long_description_ar', 'notes', 'rejection_reason', 'payload', 'exception', 'content', 'content_en', 'content_ar'], true)) {
            return 'LONGTEXT';
        }

        return 'TEXT';
    }

    if (str_contains($type, 'blob')) {
        return 'LONGBLOB';
    }

    if (preg_match('/varchar\s*\((\d+)\)/', $type, $matches)) {
        return 'VARCHAR('.$matches[1].')';
    }

    if (str_contains($type, 'char')) {
        return 'VARCHAR(255)';
    }

    return 'VARCHAR(255)';
}

function formatDefaultValue(string $default, string $mysqlType): string
{
    $trimmed = trim($default);

    if (strtoupper($trimmed) === 'NULL') {
        return 'NULL';
    }

    if (preg_match("/^'(.*)'$/s", $trimmed, $matches)) {
        $value = str_replace("'", "''", $matches[1]);

        if (str_contains($mysqlType, 'INT') || str_contains($mysqlType, 'DECIMAL') || str_contains($mysqlType, 'TINYINT')) {
            return $value === '' ? '0' : $value;
        }

        return "'".$value."'";
    }

    if (is_numeric($trimmed)) {
        return $trimmed;
    }

    return "'".str_replace("'", "''", $trimmed)."'";
}

function extractForeignKeys(string $table, string $createSql): array
{
    $foreignKeys = [];

    if (preg_match_all('/foreign key\s*\("([^"]+)"\)\s*references\s+"?([^"\(\s]+)"?\s*\("([^"]+)"\)([^,\)]*)/i', $createSql, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $onDelete = 'RESTRICT';
            $onUpdate = 'RESTRICT';

            if (preg_match('/on delete (cascade|set null|restrict|no action)/i', $match[4], $deleteMatch)) {
                $onDelete = strtoupper(str_replace(' ', ' ', trim($deleteMatch[1])));
                if ($onDelete === 'NO ACTION') {
                    $onDelete = 'RESTRICT';
                }
            }

            if (preg_match('/on update (cascade|set null|restrict|no action)/i', $match[4], $updateMatch)) {
                $onUpdate = strtoupper(str_replace(' ', ' ', trim($updateMatch[1])));
                if ($onUpdate === 'NO ACTION') {
                    $onUpdate = 'RESTRICT';
                }
            }

            $constraintName = foreignKeyConstraintName($table, $match[1], $match[2]);
            $foreignKeys[] = 'CONSTRAINT `'.$constraintName.'` FOREIGN KEY (`'.$match[1].'`) REFERENCES `'.$match[2].'` (`'.$match[3].'`) ON DELETE '.$onDelete.' ON UPDATE '.$onUpdate;
        }
    }

    return $foreignKeys;
}

function foreignKeyConstraintName(string $table, string $column, string $referencedTable): string
{
    $name = 'fk_'.$table.'_'.$column.'_'.$referencedTable;
    $name = strtolower(preg_replace('/[^a-z0-9_]/', '_', $name) ?? $name);

    if (strlen($name) <= 64) {
        return $name;
    }

    return substr($name, 0, 32).'_'.substr(md5($table.$column.$referencedTable), 0, 31);
}

function buildIndexes(PDO $pdo, string $table, array $columns): array
{
    $indexes = [];
    $primaryColumn = null;

    foreach ($columns as $column) {
        if ((int) $column['pk'] === 1) {
            $primaryColumn = $column['name'];
        }
    }

    $indexRows = $pdo->query("SELECT name, sql FROM sqlite_master WHERE type='index' AND tbl_name=".$pdo->quote($table))->fetchAll(PDO::FETCH_ASSOC);

    foreach ($indexRows as $indexRow) {
        $indexName = $indexRow['name'];
        $indexSql = (string) $indexRow['sql'];

        if ($indexName === null || str_starts_with($indexName, 'sqlite_autoindex')) {
            continue;
        }

        if ($indexSql === '') {
            continue;
        }

        if (preg_match('/create unique index/i', $indexSql)) {
            if (preg_match('/\(([^\)]+)\)/', $indexSql, $match)) {
                $cols = array_map(static fn (string $col) => '`'.trim(str_replace('"', '', $col)).'`', explode(',', $match[1]));
                $indexes[] = 'UNIQUE KEY `'.$indexName.'` ('.implode(', ', $cols).')';
            }

            continue;
        }

        if (preg_match('/create index/i', $indexSql)) {
            if (preg_match('/\(([^\)]+)\)/', $indexSql, $match)) {
                $cols = array_map(static fn (string $col) => '`'.trim(str_replace('"', '', $col)).'`', explode(',', $match[1]));
                if ($primaryColumn !== null && count($cols) === 1 && $cols[0] === '`'.$primaryColumn.'`') {
                    continue;
                }
                $indexes[] = 'KEY `'.$indexName.'` ('.implode(', ', $cols).')';
            }
        }
    }

    return $indexes;
}

function buildInserts(PDO $pdo, string $table): array
{
    $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);

    if ($rows === []) {
        return [];
    }

    $columns = array_keys($rows[0]);
    $columnList = implode(', ', array_map(static fn (string $column) => '`'.$column.'`', $columns));
    $inserts = [];
    $batch = [];
    $batchSize = 100;

    foreach ($rows as $row) {
        $values = [];

        foreach ($columns as $column) {
            $values[] = formatInsertValue($row[$column]);
        }

        $batch[] = '('.implode(', ', $values).')';

        if (count($batch) >= $batchSize) {
            $inserts[] = "INSERT INTO `{$table}` ({$columnList}) VALUES\n".implode(",\n", $batch).';';
            $batch = [];
        }
    }

    if ($batch !== []) {
        $inserts[] = "INSERT INTO `{$table}` ({$columnList}) VALUES\n".implode(",\n", $batch).';';
    }

    return $inserts;
}

function formatInsertValue(mixed $value): string
{
    if ($value === null) {
        return 'NULL';
    }

    if (is_int($value) || is_float($value)) {
        return (string) $value;
    }

    if (is_bool($value)) {
        return $value ? '1' : '0';
    }

    $stringValue = (string) $value;
    $stringValue = str_replace('\\', '\\\\', $stringValue);
    $stringValue = str_replace("'", "''", $stringValue);
    $stringValue = str_replace("\0", '\\0', $stringValue);
    $stringValue = str_replace("\n", '\\n', $stringValue);
    $stringValue = str_replace("\r", '\\r', $stringValue);

    return "'".$stringValue."'";
}
