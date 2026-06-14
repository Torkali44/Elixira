<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetMonthlyRewardPoints extends Command
{
    protected $signature = 'points:reset-monthly';

    protected $description = 'Reset all user reward points to zero on the first day of each month';

    public function handle(): int
    {
        $updated = User::query()->where('total_points', '>', 0)->update(['total_points' => 0]);

        $this->info("Reset reward points for {$updated} user(s).");

        return self::SUCCESS;
    }
}
