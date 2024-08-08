<?php

namespace App\Models\Prompt;

class PromptGenerate extends \AIGenerate\Models\Prompt\PromptGenerate
{
    protected $connection = 'api';
    protected $table = 'ai_generate.prompt_generates';

}
