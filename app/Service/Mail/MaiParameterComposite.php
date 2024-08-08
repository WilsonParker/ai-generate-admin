<?php

namespace App\Service\Mail;

use Illuminate\Support\Arr;

class MaiParameterComposite
{

    /**
     * @var $composites array<\App\Service\Mail\Composites\Contracts\ParameterContracts>
     */
    public function __construct(protected array $composites) {}

    public function bindParams(array $params, array $data = []): array
    {
        $result = [];
        foreach ($this->composites as $composite) {
            if ($composite->hasParams($params)) {
                $result = array_merge($result, $composite->bindParams($params, $data));
            }
        }
        return Arr::undot($result);
    }

}