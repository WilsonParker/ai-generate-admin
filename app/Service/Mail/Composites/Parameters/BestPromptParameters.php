<?php

namespace App\Service\Mail\Composites\Parameters;

use App\Service\Prompt\PromptService;

class BestPromptParameters extends BaseParameters
{

    public function __construct(
        private readonly PromptService $promptService,
    ) {}

    public function bindParams(array $params, $data = null): array
    {
        $prompts = $this->promptService->getBestPrompts();
        $result = [];
        /*$result['params']['best']['prompt'] = [];
        $prompts->each(function ($prompt, $key) use (&$result) {
            $param = $this->bindParam($key);
            $result['params']['best']['prompt']['link'][$param] = $prompt->getLink();
            $result['params']['best']['prompt']['thumbnail'][$param] = $prompt->getThumbnailUrl();
            $result['params']['best']['prompt']['name'][$param] = $prompt->getName();
        });*/
        $prompts->each(function ($prompt, $key) use (&$result) {
            $param = $this->bindParam($key);
            $result["params.best.prompt.link.$param"] = $prompt->getLink();
            $result["params.best.prompt.thumbnail.$param"] = $prompt->getThumbnailUrl();
            $result["params.best.prompt.name.$param"] = $prompt->getName();
        });
        return $result;
    }

    public function bindParam(string $param, $data = null): string
    {
        return match ($param) {
            "0" => 'one',
            "1" => 'two',
            "2" => 'three',
            "3" => 'four',
        };
    }

    public function getParams(): array
    {
        return [
            "params.best.prompt.link.one",
            "params.best.prompt.thumbnail.one",
            "params.best.prompt.name.one",
            "params.best.prompt.link.two",
            "params.best.prompt.thumbnail.two",
            "params.best.prompt.name.two",
            "params.best.prompt.link.three",
            "params.best.prompt.thumbnail.three",
            "params.best.prompt.name.three",
            "params.best.prompt.link.four",
            "params.best.prompt.thumbnail.four",
            "params.best.prompt.name.four",
        ];
    }
}