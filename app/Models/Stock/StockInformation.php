<?php

namespace App\Models\Stock;

class StockInformation extends \AIGenerate\Models\Stock\StockInformation
{
    protected $connection = 'api';
    protected $table = 'ai_generate.stock_information';

}
