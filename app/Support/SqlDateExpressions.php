<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class SqlDateExpressions
{
    public static function yearMonth(string $column = 'created_at'): string
    {
        return match (DB::connection()->getDriverName()) {
            'mysql', 'mariadb' => "DATE_FORMAT({$column}, '%Y-%m')",
            'pgsql' => "TO_CHAR({$column}, 'YYYY-MM')",
            default => "strftime('%Y-%m', {$column})",
        };
    }
}
