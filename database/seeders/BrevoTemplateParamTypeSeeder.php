<?php

namespace Database\Seeders;

use App\Models\Brevo\BrevoTemplateParamType;
use App\Service\Mail\Brevo\Enums\ParamTypes;
use Illuminate\Database\Seeder;

class BrevoTemplateParamTypeSeeder extends Seeder
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

        collect(ParamTypes::cases())->each(fn($item) => BrevoTemplateParamType::create($build($item->value, $item->value)));
    }
}
