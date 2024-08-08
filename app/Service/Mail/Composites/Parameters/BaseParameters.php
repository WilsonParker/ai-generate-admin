<?php

namespace App\Service\Mail\Composites\Parameters;

use App\Service\Mail\Composites\Contracts\ParameterContracts;
use Illuminate\Support\Arr;

abstract class BaseParameters implements ParameterContracts
{

    public function hasParams(array $params): bool
    {
        return Arr::first($params, fn($param) => in_array($param, $this->getParams())) != null;
    }

}