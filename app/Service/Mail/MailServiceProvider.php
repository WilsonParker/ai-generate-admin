<?php

namespace App\Service\Mail;

use App\Service\Mail\Brevo\BreveService;
use App\Service\Mail\Composites\Parameters\BestPromptParameters;
use App\Service\Mail\Composites\Parameters\UserParameters;
use App\Service\Mail\Contracts\MailContract;
use App\Service\Mail\Contracts\MailParameterContract;
use App\Service\Prompt\PromptService;
use Inertia\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            BestPromptParameters::class,
            fn($app) => new BestPromptParameters($app->make(PromptService::class))
        );
        $this->app->singleton(
            UserParameters::class,
            fn() => new UserParameters()
        );
        $this->app->singleton(MaiParameterComposite::class,
            fn() => new MaiParameterComposite([
                $this->app->make(UserParameters::class),
                $this->app->make(BestPromptParameters::class),
            ])
        );

        $this->app->singleton(
            BreveService::class,
            fn($app) => new BreveService(
                config('brevo.api_key'),
                $app->make(MaiParameterComposite::class)
            )
        );

        $this->app->bind(
            MailContract::class,
            BreveService::class
        );
    }

}