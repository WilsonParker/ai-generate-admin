<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Relations\MorphMany;

class StockGenerate extends \AIGenerate\Models\Stock\StockGenerate
{
    protected $connection = 'api';
    protected $table = 'ai_generate.stock_generates';

    public function images(): MorphMany
    {
        return $this->morphMany(\App\Services\Image\Models\Media::class, 'gallery', 'model_type', 'model_id', 'id');
    }
}
