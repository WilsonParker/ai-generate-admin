<?php

namespace App\Nova\Resources;

use App\Nova\Traits\EnumToSelectOptionsTraits;

abstract class BaseResource extends Resource
{
    use EnumToSelectOptionsTraits, \Itsmejoshua\Novaspatiepermissions\PermissionsBasedAuthTrait;

    public static $permissionsForAbilities = [];
}
