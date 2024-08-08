<?php

namespace App\Nova\Traits;

trait EnumToSelectOptionsTraits
{
    protected function enumToSelectOptions($enum): array
    {
        return collect($enum::cases())
            ->mapWithKeys(fn($item) => [$item->value => $item->name])
            ->toArray();
    }

    protected function enumToSelectOptionsReverse($enum): array
    {
        return collect($enum::cases())
            ->mapWithKeys(fn($item) => [$item->name => $item->value])
            ->toArray();
    }
}
