<?php

namespace App\Provider;

use App\Models\Generate\TextGenerate;
use App\Models\Image\Media;
use App\Models\Prompt\Prompt;
use App\Models\Stock\Stock;
use App\Models\Stock\StockGenerate;
use App\Models\User\UserInformation;
use App\Observer\Image\MediaObserver;
use App\Observer\Prompt\PromptObserver;
use App\Observer\Stock\StockObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
//        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
//        $this->app->register(TelescopeServiceProvider::class);
        if (env('APP_DEBUG')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'prompt' => Prompt::class,
            'stock' => Stock::class,
            'stock-generates' => StockGenerate::class,
            'text-generates' => TextGenerate::class,
            'user-information' => UserInformation::class,
        ]);

        Nova::serving(function () {
            // Media::observe(MediaObserver::class);
        });

        Prompt::observe(PromptObserver::class);
        Stock::observe(StockObserver::class);
        Media::observe(MediaObserver::class);
    }
}
