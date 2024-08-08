<?php

namespace App\Policies\Stock;

use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy
{
    use HandlesAuthorization;

    public function viewAny()
    {
        return true;
    }
}
