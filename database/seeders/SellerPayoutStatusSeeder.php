<?php

namespace Database\Seeders;

use App\Models\SellerPayout\SellerPayoutStatus;
use App\Service\SellerPayout\Enums\Status;
use Illuminate\Database\Seeder;

class SellerPayoutStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $build = function (string $code, string $name): array {
            return [
                'code' => $code,
                'name' => $name,
            ];
        };

        collect(Status::cases())->each(fn($item) => SellerPayoutStatus::create($build($item->value, $item->value)));
    }
}
