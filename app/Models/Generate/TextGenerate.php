<?php

namespace App\Models\Generate;

use Illuminate\Database\Eloquent\Relations\MorphMany;

class TextGenerate extends \AIGenerate\Models\TextGenerate\TextGenerate
{
    protected $connection = 'api';
    protected $table = 'ai_generate.text_generates';

    public function images(): MorphMany
    {
        return $this->morphMany(\App\Services\Image\Models\Media::class, 'gallery', 'model_type', 'model_id', 'id');
    }
}
