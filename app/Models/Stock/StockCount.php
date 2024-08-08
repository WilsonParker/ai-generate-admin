<?php

namespace App\Models\Stock;

class StockCount extends \AIGenerate\Models\Stock\StockCount
{
    protected $connection = 'api';
    protected $table = 'ai_generate.stock_count';

    public function stock(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }
}
