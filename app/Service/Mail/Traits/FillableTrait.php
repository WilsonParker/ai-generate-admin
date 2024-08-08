<?php

namespace App\Service\Mail\Traits;

trait FillableTrait
{
    function fill(array $attributes): static
    {
        $fillable = $this->fillableFromArray($attributes);
        foreach ($fillable as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    function fillableFromArray(array $attributes): array
    {
        if (count($this->getFillable()) > 0) {
            return array_intersect_key($attributes, array_flip($this->getFillable()));
        }
        return $attributes;
    }

    function getFillable(): array
    {
        return $this->fillable;
    }

}