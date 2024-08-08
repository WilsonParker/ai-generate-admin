<?php

namespace App\Service\Mail\Composites\Parameters;

use Illuminate\Support\Arr;

class UserParameters extends BaseParameters
{

    public function bindParams(array $params, $data = null): array
    {
        return collect($params)
            ->filter(fn($param) => in_array($param, $this->getParams()))
            ->mapWithKeys(fn($param, $key) => [$param => $this->bindParam($param, $data)])->toArray();
    }

    public function bindParam(string $param, $data = null): string
    {
        return match ($param) {
            'params.user.name' => $data['user']->getName(),
            'params.mypage.link' => $data['user']->getMyPageLink(),
        };
    }

    public function getParams(): array
    {
        return [
            'params.user.name',
            'params.mypage.link',
        ];
    }
}