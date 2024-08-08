<?php

namespace App\Providers;

use App\Nova\Resources\Blog\Blog;
use App\Nova\Resources\Blog\BlogAuthor;
use App\Nova\Resources\Blog\BlogCategory;
use App\Nova\Resources\Enterprise\Enterprise;
use App\Nova\Resources\Enterprise\EnterpriseClient;
use App\Nova\Resources\Enterprise\EnterpriseClientPivot;
use App\Nova\Resources\Enterprise\EnterpriseRequest;
use App\Nova\Resources\Generate\StockGenerate;
use App\Nova\Resources\Generate\TextGenerate;
use App\Nova\Resources\Images\PromptGenerateResult;
use App\Nova\Resources\Mail\Sender;
use App\Nova\Resources\Mail\Template;
use App\Nova\Resources\OpenAI\OpenAiKey;
use App\Nova\Resources\Prompt\Prompt;
use App\Nova\Resources\SellerPayout\SellerPayout;
use App\Nova\Resources\Stock\Information;
use App\Nova\Resources\Stock\Origin;
use App\Nova\Resources\Stock\Stock;
use App\Nova\Resources\Stock\StockCount;
use App\Nova\Resources\User\AdminUser;
use App\Nova\Resources\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Itsmejoshua\Novaspatiepermissions\Novaspatiepermissions;
use Laravel\Nova\Dashboards\Main;
use Laravel\Nova\Menu\MenuGroup;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::withBreadcrumbs();

        Nova::mainMenu(function (Request $request) {
            $menus = [
                MenuSection::dashboard(Main::class)->icon('chart-bar'),

                MenuSection::make('User', [
                    MenuItem::resource(User::class),
                    MenuItem::resource(AdminUser::class),
                ])->icon('user')->collapsable(),

                MenuSection::make('Api', [
                    MenuItem::resource(OpenAiKey::class),
                    // MenuItem::resource(GoogleToken::class),
                ])->icon('code')->collapsable(),

                MenuSection::make('Content', [
                    MenuGroup::make('Stock', [
                        MenuItem::resource(Stock::class),
                        MenuItem::resource(Information::class),
                        MenuItem::resource(Origin::class),
                        MenuItem::resource(StockCount::class),
                    ]),
                    MenuGroup::make('Generate', [
                        MenuItem::resource(StockGenerate::class),
                        MenuItem::resource(TextGenerate::class),
                    ]),
                    MenuGroup::make('Prompt', [
                        MenuItem::resource(Prompt::class),
                        MenuItem::resource(PromptGenerateResult::class),
                    ]),
                    MenuGroup::make('Blog', [
                        MenuItem::resource(BlogCategory::class),
                        MenuItem::resource(BlogAuthor::class),
                        MenuItem::resource(Blog::class),
                    ]),
                    MenuGroup::make('Enterprise', [
                        MenuItem::resource(Enterprise::class),
                        MenuItem::resource(EnterpriseClient::class),
                        MenuItem::resource(EnterpriseClientPivot::class),
                        MenuItem::resource(EnterpriseRequest::class),
                    ]),
                ])->icon('document-text')->collapsable(),


                MenuSection::make('Payment', [
                    MenuItem::resource(SellerPayout::class),
                ])->icon('currency-dollar')->collapsable(),

                MenuSection::make('Mail', [
                    MenuItem::resource(Template::class),
                    MenuItem::resource(Sender::class),
                ])->icon('mail')->collapsable(),

            ];

            if ($request->user()?->isAdmin()) {
                return [
                    ...$menus,
                    Novaspatiepermissions::make()->menu($request),
                ];
            }

            return $menus;
        });
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        if (request()->user()?->isAdmin()) {
            return [
                // ...
                Novaspatiepermissions::make(),
            ];
        } else {
            return [

            ];
        }
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // Nova::initialPath('/resources/users');
    }

    /**
     * Register the Nova gate.
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, \App\Models\User\AdminUser::all()->pluck('email')->toArray());
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }
}
