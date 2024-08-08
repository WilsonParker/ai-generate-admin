<?php

namespace App\Service\Mail\Composites\Contracts;

use Illuminate\Support\Collection;

interface ParameterContracts
{
    public function hasParams(array $params): bool;

    public function bindParams(array $params, $data = null): array;

    public function bindParam(string $param, $data = null): string;
    public function getParams(): array;
}