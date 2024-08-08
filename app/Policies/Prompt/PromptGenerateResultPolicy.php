<?php

namespace App\Policies\Prompt;

use Illuminate\Auth\Access\HandlesAuthorization;

class PromptGenerateResultPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(): bool
    {
        return false;
    }

    public function update(): bool
    {
        return false;
    }

    public function view(): bool
    {
        return true;
    }
}
