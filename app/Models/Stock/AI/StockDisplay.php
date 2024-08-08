<?php

namespace App\Models\Stock\AI;

use Illuminate\Database\Eloquent\Model;

class StockDisplay extends Model
{
    protected $connection = 'ai';
    protected $table = 'stock_displays';
    protected $fillable = [
        'stock_id',
        'src',
        'key',
        'width',
        'height',
    ];
}
