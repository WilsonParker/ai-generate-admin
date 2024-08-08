<?php

namespace App\Models\Stock;

class StockRecommend extends \AIGenerate\Models\Stock\StockRecommend
{
    protected $connection = 'api';
    protected $table = 'ai_generate.stock_recommends';

    public function stock(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }
}
