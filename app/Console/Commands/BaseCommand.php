<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseCommand extends Command
{
    protected function transaction(callable $callback): void
    {
        try {
            DB::beginTransaction();
            $callback();
            DB::commit();
        } catch (\Throwable $throwable) {
            Log::info('Seller payout schedule error: ' . $throwable);
            DB::rollBack();
        }
    }
}
