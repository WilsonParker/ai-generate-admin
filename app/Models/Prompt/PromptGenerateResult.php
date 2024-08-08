<?php

namespace App\Models\Prompt;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptGenerateResult extends \AIGenerate\Models\Prompt\PromptGenerateResult
{
    protected $connection = 'api';

}