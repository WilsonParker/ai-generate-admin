<?php

namespace App\Provider;

use App\Http\Controllers\Prompt\ChatPromptGenerateController;
use App\Http\Controllers\Prompt\CompletionPromptGenerateController;
use App\Http\Controllers\Prompt\ImagePromptGenerateController;
use App\Http\Controllers\Prompt\PromptFavoriteController;
use App\Http\Repositories\OpenAI\OpenAiKeyRepository;
use App\Http\Repositories\Prompt\Contracts\PromptRepositoryContract;
use App\Http\Repositories\Prompt\PromptCategoryRepository;
use App\Http\Repositories\Prompt\PromptEngineRepository;
use App\Http\Repositories\Prompt\PromptFavoriteRepository;
use App\Http\Repositories\Prompt\PromptGenerateOutputOptionRepository;
use App\Http\Repositories\Prompt\PromptGenerateRepository;
use App\Http\Repositories\Prompt\PromptGenerateResultRepository;
use App\Http\Repositories\Prompt\PromptInfinityScrollRepository;
use App\Http\Repositories\Prompt\PromptOptionRepository;
use App\Http\Repositories\Prompt\PromptTypeRepository;
use App\Http\Repositories\Prompt\PromptViewRepository;
use App\Http\Repositories\User\UserRepository;
use App\Models\OpenAI\OpenAiKey;
use App\Models\OpenAI\OpenAiKeyStack;
use App\Models\Prompt\Prompt;
use App\Models\Prompt\PromptCategory;
use App\Models\Prompt\PromptEngine;
use App\Models\Prompt\PromptFavorite;
use App\Models\Prompt\PromptGenerate;
use App\Models\Prompt\PromptGenerateResult;
use App\Models\Prompt\PromptOption;
use App\Models\Prompt\PromptType;
use App\Models\Prompt\PromptView;
use App\Models\Stock\Stock;
use App\Models\User\User;
use App\Observer\Prompt\PromptObserver;
use App\Repository\Mail\SenderRepository;
use App\Repository\Stock\StockRepository;
use App\Service\Mail\Model\Sender;
use App\Service\Prompt\PromptService;
use App\Service\Stock\StockService;
use App\Services\Auth\AuthService;
use App\Services\Image\Contracts\ImageServiceContract;
use App\Services\Prompt\Contracts\PromptGenerateServiceContract;
use App\Services\Prompt\Contracts\PromptServiceContract;
use App\Services\Prompt\FreeGenerateComposite\Contracts\CanGenerateForFree;
use App\Services\Prompt\FreeGenerateComposite\FirstGenerateComposite;
use App\Services\Prompt\FreeGenerateComposite\FirstGenerateUsingConstantComposite;
use App\Services\Prompt\FreeGenerateComposite\GenerateComposite;
use App\Services\Prompt\PromptChatGenerateService;
use App\Services\Prompt\PromptCompletionGenerateService;
use App\Services\Prompt\PromptDecorators\Decorators;
use App\Services\Prompt\PromptDecorators\LastOrderDecorator;
use App\Services\Prompt\PromptDecorators\NullableAnswerDecorator;
use App\Services\Prompt\PromptFavoriteService;
use App\Services\Prompt\PromptImageGenerateService;
use App\Services\Prompt\PromptInfinityService;
use App\Services\Prompt\Sorts\HottestSort;
use App\Services\Prompt\Sorts\NewestSort;
use App\Services\Prompt\Sorts\OldestSort;
use App\Services\Prompt\Sorts\RelevanceSort;
use App\Services\Prompt\Sorts\Sorts;
use App\Services\Prompt\Sorts\TopSort;
use App\Services\Prompt\ThumbnailComposite\DallEThumbnailComposite;
use App\Services\Prompt\ThumbnailComposite\DefaultThumbnailComposite;
use App\Services\Prompt\ThumbnailComposite\ThumbnailComposite;
use App\Services\Tag\TagService;
use App\Services\User\UserConstantService;
use Illuminate\Support\ServiceProvider;
use AIGenerate\Services\AI\OpenAI\Chat\ApiService;

class ControllerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerUser();
        $this->registerPrompt();
        $this->registerApiPrompt();
        $this->registerFacades();
        $this->registerStock();
        $this->registerMail();
    }

    private function registerUser()
    {
        $this->app->singleton(
            UserRepository::class,
            fn($app) => new UserRepository(User::class)
        );
    }

    private function registerPrompt()
    {
        $this->app->singleton(
            PromptInfinityScrollRepository::class,
            fn($app) => new PromptInfinityScrollRepository(Prompt::class)
        );

        $this->app->singleton(
            PromptService::class,
            fn($app) => new PromptService($app->make(PromptInfinityScrollRepository::class))
        );

        // OBSERVER
        $this->app->singleton(
            PromptObserver::class,
            fn($app) => new PromptObserver(
                $app->make(PromptService::class),
            )
        );
    }

    private function registerApiPrompt()
    {
        // DECORATORS
        $this->app->singleton(
            Decorators::class,
            fn($app) => new Decorators([
                $this->app->make(LastOrderDecorator::class),
                $this->app->make(NullableAnswerDecorator::class),
            ])
        );

        $this->app->singleton(
            PromptRepositoryContract::class,
            fn() => new PromptInfinityScrollRepository(Prompt::class)
        );
        $this->app->singleton(
            PromptOptionRepository::class,
            fn() => new PromptOptionRepository(PromptOption::class)
        );
        $this->app->singleton(
            PromptCategoryRepository::class,
            fn() => new PromptCategoryRepository(PromptCategory::class)
        );
        $this->app->singleton(
            PromptTypeRepository::class,
            fn() => new PromptTypeRepository(PromptType::class)
        );
        $this->app->singleton(
            PromptEngineRepository::class,
            fn() => new PromptEngineRepository(PromptEngine::class)
        );
        $this->app->singleton(
            PromptFavoriteRepository::class,
            fn() => new PromptFavoriteRepository(PromptFavorite::class)
        );
        $this->app->singleton(
            PromptGenerateRepository::class,
            fn() => new PromptGenerateRepository(PromptGenerate::class)
        );
        $this->app->singleton(
            PromptGenerateResultRepository::class,
            fn() => new PromptGenerateResultRepository(PromptGenerateResult::class)
        );
        $this->app->singleton(
            PromptGenerateOutputOptionRepository::class,
            fn() => new PromptGenerateOutputOptionRepository('')
        );
        $this->app->singleton(
            PromptViewRepository::class,
            fn() => new PromptViewRepository(PromptView::class)
        );
        $this->app->singleton(
            OpenAiKeyRepository::class,
            fn() => new OpenAiKeyRepository(OpenAIKey::class, OpenAiKeyStack::class)
        );

        // COMPOSITE
        $this->app->singleton(
            FirstGenerateComposite::class,
            fn($app) => new FirstGenerateComposite(
                $this->app->make(PromptGenerateRepository::class),
            )
        );
        $this->app->singleton(
            FirstGenerateUsingConstantComposite::class,
            fn($app) => new FirstGenerateUsingConstantComposite(
                $this->app->make(UserConstantService::class),
            )
        );
        $this->app->singleton(
            CanGenerateForFree::class,
            fn($app) => new GenerateComposite([
                $this->app->make(FirstGenerateUsingConstantComposite::class),
            ])
        );

        $this->app->singleton(
            DallEThumbnailComposite::class,
            fn($app) => new DallEThumbnailComposite()
        );
        $this->app->singleton(
            DefaultThumbnailComposite::class,
            fn($app) => new DefaultThumbnailComposite(
                $this->app->make(ImageServiceContract::class),
            )
        );
        $this->app->singleton(
            ThumbnailComposite::class,
            fn($app) => new ThumbnailComposite([
                $this->app->make(DallEThumbnailComposite::class),
                $this->app->make(DefaultThumbnailComposite::class),
            ])
        );

        // SERVICES
        $this->app->singleton(
            PromptImageGenerateService::class,
            fn($app) => new PromptImageGenerateService(
                $app->make(PromptGenerateRepository::class),
                $app->make(PromptGenerateResultRepository::class),
                $app->make(OpenAiKeyRepository::class),
                $app->make(\AIGenerate\Services\AI\OpenAI\Images\ApiService::class)
            )
        );
        $this->app->when(ImagePromptGenerateController::class)
                  ->needs(PromptGenerateServiceContract::class)
                  ->give(PromptImageGenerateService::class);

        $this->app->singleton(
            PromptChatGenerateService::class,
            fn($app) => new PromptChatGenerateService(
                $app->make(PromptGenerateRepository::class),
                $app->make(PromptGenerateResultRepository::class),
                $app->make(OpenAiKeyRepository::class),
                $app->make(ApiService::class),
                $app->make(Decorators::class),
            )
        );
        $this->app->when(ChatPromptGenerateController::class)
                  ->needs(PromptGenerateServiceContract::class)
                  ->give(PromptChatGenerateService::class);

        $this->app->singleton(
            PromptCompletionGenerateService::class,
            fn($app) => new PromptCompletionGenerateService(
                $app->make(PromptGenerateRepository::class),
                $app->make(PromptGenerateResultRepository::class),
                $app->make(OpenAiKeyRepository::class),
                $app->make(\AIGenerate\Services\AI\OpenAI\Completion\ApiService::class),
                $app->make(Decorators::class),
            )
        );
        $this->app->when(CompletionPromptGenerateController::class)
                  ->needs(PromptGenerateServiceContract::class)
                  ->give(PromptCompletionGenerateService::class);

        $this->app->singleton(
            PromptServiceContract::class,
            fn($app) => new PromptInfinityService(
                $app->make(PromptRepositoryContract::class),
                $app->make(PromptCategoryRepository::class),
                $app->make(PromptTypeRepository::class),
                $app->make(PromptEngineRepository::class),
                $app->make(PromptGenerateOutputOptionRepository::class),
                $app->make(TagService::class),
                $app->make(ImageServiceContract::class),
                new Sorts([
                    $app->make(RelevanceSort::class),
                    $app->make(HottestSort::class),
                    $app->make(TopSort::class),
                    $app->make(NewestSort::class),
                    $app->make(OldestSort::class),
                ]),
                $app->make(ThumbnailComposite::class),
            )
        );

        $this->app->singleton(
            PromptFavoriteService::class,
            fn($app) => new PromptFavoriteService(
                $app->make(PromptFavoriteRepository::class),
            )
        );

        $this->app->when(PromptFavoriteController::class)
                  ->needs(PromptFavoriteService::class)
                  ->give(PromptFavoriteService::class);
    }

    private function registerFacades()
    {
        $this->app->singleton('authService', function ($app) {
            return new AuthService($app->make(UserRepository::class));
        });
    }

    private function registerMail()
    {
        $this->app->singleton(
            SenderRepository::class,
            fn($app) => new SenderRepository(Sender::class)
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    private function registerStock()
    {
        $this->app->singleton(
            StockRepository::class,
            fn($app) => new StockRepository(Stock::class)
        );
        $this->app->singleton(
            StockService::class,
            fn($app) => new StockService(
                $app->make(StockRepository::class),
            )
        );
    }

}
