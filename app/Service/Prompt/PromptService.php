<?php

namespace App\Service\Prompt;

use App\Http\Repositories\Prompt\Contracts\PromptDetailRepositoryContract;
use App\Models\Prompt\Prompt;
use App\Models\Prompt\PromptOption;

class PromptService
{
    public function __construct(
        private readonly PromptDetailRepositoryContract $repository,
    ) {}

    public function storeOptions(
        Prompt $prompt,
    ): Prompt {
        $matches = $this->matchOptions($prompt->template);
        $options = collect($matches);
        $prompt->options()->delete();
        $prompt->options()->saveMany($options->map(fn($option) => new PromptOption(['name' => $option])));
        $prompt->load('options');
        return $prompt;
    }

    public function matchOptions(string $template): array
    {
        preg_match_all('/\[(.*?)\]/', $template, $matches);
        return collect($matches[1])->map(fn($item) => strtoupper($item))->unique()->toArray();
    }

    public function getBestPrompts(): \Illuminate\Support\Collection
    {
        return $this->repository->popularPrompts(4);
    }
}
