<?php

namespace App\Models\Stock\AI;

use App\Models\Image\AI\Media;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;

class Stock extends \AIGenerate\Models\Stock\Stock
{
    use SoftDeletes, InteractsWithMedia;

    protected $connection = 'ai';
    protected $table = 'ai_generate_crawler.stocks';

    protected $with = [
        'images',
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Media::class, 'gallery', 'model_type', 'model_id', 'id');
    }

    public function getMorphClass(): string
    {
        return 'App\Models\Stocks\Stock';
    }

    public function displays(): HasMany
    {
        return $this->hasMany(StockDisplay::class, 'stock_id', 'id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
             ->useDisk('ai')
             ->useFallbackUrl(config('constant.images.default') ?? '')
             ->registerMediaConversions(function (Media $media) {
                 $this
                     ->addMediaConversion('gallery-thumbnail')
                     ->format(Manipulations::FORMAT_WEBP)
                     ->width(368)
                     ->height(232);
             });
    }
}
