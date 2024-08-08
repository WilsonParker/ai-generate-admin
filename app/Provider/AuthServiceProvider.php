<?php

namespace App\Provider;

// use Illuminate\Support\Facades\Gate;
use App\Models\Prompt\PromptGenerateResult;
use App\Models\SellerPayout\SellerPayout;
use App\Policies\Prompt\PromptGenerateResultPolicy;
use App\Policies\SellerPayout\SellerPayoutPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PromptGenerateResult::class => PromptGenerateResultPolicy::class,
        SellerPayout::class => SellerPayoutPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
