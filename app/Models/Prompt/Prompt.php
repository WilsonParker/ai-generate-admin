<?php

namespace App\Models\Prompt;

class Prompt extends \AIGenerate\Models\Prompt\Prompt
{
    protected $connection = 'api';
    protected $table = 'ai_generate.prompts';

    public function options(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PromptOption::class);
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnail ? $this->thumbnail->getLink('gallery-thumbnail') : config('constant.images.default');
    }

    public function getLink(): string
    {
        return config('constant.front.root') . '/prompt/detail/' . $this->getKey();
    }

    public function getName(): string
    {
        return $this->title;
    }

}
