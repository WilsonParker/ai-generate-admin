<?php

namespace App\Observer\Prompt;

use App\Models\Prompt\Prompt;
use App\Service\Prompt\PromptService;

class PromptObserver
{
    public function __construct(private readonly PromptService $service)
    {
    }
    public function created(Prompt $prompt): void
    {
        $prompt->count()->create();
    }

    public function updated(Prompt $prompt): void
    {
        if($prompt->isDirty('template')) {
            $this->service->storeOptions($prompt);
        }
    }
}
