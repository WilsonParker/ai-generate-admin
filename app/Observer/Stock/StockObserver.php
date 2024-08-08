<?php

namespace App\Observer\Stock;

use App\Models\Stock\Stock;
use App\Repository\Stock\StockRepository;

class StockObserver
{
    public function __construct(private readonly StockRepository $repository)
    {
    }

    public function updating(Stock $stock): void
    {
        if ($stock->isDirty('is_recommend')) {
            $this->repository->updateRecommend($stock, $stock->is_recommend);
            unset($stock->is_recommend);
        }
    }
}
