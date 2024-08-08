<?php

namespace App\Models\Stock;

use App\Models\Image\Media;
use App\Models\Stock\AI\StockGenerateInformation;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use AIGenerate\Services\Stock\Contracts\StockModelContract;
use Spatie\MediaLibrary\MediaCollections\Models\Concerns\IsSorted;

class Stock extends \AIGenerate\Models\Stock\Stock implements StockModelContract
{
    protected $connection = 'api';
    protected $table = 'ai_generate.stocks';
    use IsSorted;

    protected $fillable = [
        'title',
        'description',
        'created_at',
        'updated_at',
    ];

    protected $with = [];

    public function images(): MorphMany
    {
        return $this->morphMany(Media::class, 'gallery', 'model_type', 'model_id', 'id');
    }

    public function origin(): HasOne
    {
        return $this->hasOne(\App\Models\Stock\AI\Stock::class, 'id', 'id');
    }

    public function getDisplay()
    {
        return $this->origin->displays->last();
    }

    public function generateInformation(): HasOne
    {
        return $this->hasOne(StockGenerateInformation::class, 'stock_id', 'id');
    }

    public function count(): HasOne
    {
        return $this->hasOne(StockCount::class, 'stock_id', 'id');
    }
}
