<?php

namespace App\Console\Commands\SellerPayout;

use App\Console\Commands\BaseCommand;
use App\Service\SellerPayout\Schedules\SellerPayoutSchedule;

class SellerPayoutCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seller:payout {Y-m?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The default is as of the current date, and when using the signature: php(sail) artisan seller:payout 2023-06';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->transaction(function () {
            $service = new SellerPayoutSchedule();
            $service->execute($service->getScheduleDate($this->argument('Y-m')));
        });
    }
}
