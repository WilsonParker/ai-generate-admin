<?php

namespace App\Repository\Stock;

use App\Http\Repositories\BaseRepository;
use App\Models\Stock\Stock;

class StockRepository extends BaseRepository
{
    public function updateRecommend(Stock $stock, bool $isRecommend)
    {
        if ($isRecommend) {
            if (!$stock->recommend()->exists()) {
                $stock->recommend()->create();
            }
        } else {
            $stock->recommend()->delete();
        }
    }
}
