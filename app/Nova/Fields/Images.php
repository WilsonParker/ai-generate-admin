<?php

namespace App\Nova\Fields;

use App\Models\Image\AI\EbessMedia;

class Images extends EbessMedia
{

    protected $defaultValidatorRules = ['image'];

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->croppable(config('nova-media-library.default-croppable', true));
    }

    public function croppable(bool $croppable): self
    {
        return $this->withMeta(compact('croppable'));
    }

    /**
     * Do we deprecate this for SingleMediaRules?
     *
     * @param $singleImageRules
     * @return \Ebess\AdvancedNovaMediaLibrary\Fields\Images
     */
    public function singleImageRules($singleImageRules): self
    {
        $this->singleMediaRules = $singleImageRules;

        return $this;
    }

    public function croppingConfigs(array $configs): self
    {
        return $this->withMeta(['croppingConfigs' => $configs]);
    }

    public function showDimensions(bool $showDimensions = true): self
    {
        return $this->showStatistics();
    }

    public function showStatistics(bool $showStatistics = true): self
    {
        return $this->withMeta(compact('showStatistics'));
    }

    public function mustCrop(bool $mustCrop = true): self
    {
        return $this->withMeta(['mustCrop' => $mustCrop]);
    }
}
