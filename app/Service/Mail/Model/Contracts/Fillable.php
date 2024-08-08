<?php

namespace App\Service\Mail\Model\Contracts;

interface Fillable
{

    function fill(array $attributes): static;

    function fillableFromArray(array $attributes);

    function getFillable(): array;
}