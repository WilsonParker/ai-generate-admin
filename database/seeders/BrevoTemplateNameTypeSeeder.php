<?php

namespace Database\Seeders;

use App\Models\Brevo\BrevoTemplateType;
use App\Service\Mail\Brevo\Enums\SendConditions;
use Illuminate\Database\Seeder;

class BrevoTemplateNameTypeSeeder extends Seeder
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

        collect(SendConditions::cases())->each(fn($item) => BrevoTemplateType::create($build($item->value, $item->value)));
    }
}
