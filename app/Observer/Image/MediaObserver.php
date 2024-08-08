<?php

namespace App\Observer\Image;

use App\Models\Image\Media;
use App\Models\Stock\Stock;
use AIGenerate\Services\Stock\StockMediaService;

class MediaObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public function __construct(private StockMediaService $stockService)
    {
    }

    public function created(Media $media): void
    {
        if ($media->model_type == Stock::class) {
            $attribute = $this->stockService->getAttributes($media->model);
            $attribute['type'] = $media->getCustomProperty('type');
            if (isset($attribute)) {
                $properties = [
                    'width',
                    'height',
                    'rWidth',
                    'rHeight',
                    'type',
                ];
                foreach ($properties as $property) {
                    $media->setCustomProperty($property, $attribute[$property]);
                    $media->save();
                }
            }
        }
    }
}
