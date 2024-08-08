<?php

namespace App\Nova\Resources\Stock;

use App\Nova\Fields\Images;
use App\Nova\Resources\BaseResource;
use App\Nova\Resources\Stock\Filters\EthnicityFilter;
use App\Nova\Resources\Stock\Filters\GenderFilter;
use App\Nova\Resources\Stock\Filters\RecommendFilter;
use App\Nova\Resources\Stock\Filters\StatusFilter;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use AIGenerate\Models\Stock\Enums\Status;

class Stock extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-stocks',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Stock>
     */
    public static $model = \App\Models\Stock\Stock::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    public static $with = ['recommend', 'media', 'origin', 'information', 'generateInformation', 'count'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
        'description',
    ];

    /**
     * Determine if this resource uses Laravel Scout.
     *
     * @return bool
     */
    public static function usesScout()
    {
        return false;
    }

    /**
     * Get the fields displayed by the resource.media
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $isRecommend = isset($this->recommend);
        return [
            ID::make()->sortable(),

            Images::make('Detail Image', 'detail')
                ->enableExistingMedia()
                ->conversionOnIndexView('detail-thumbnail')
                ->conversionOnDetailView('detail-thumbnail')
                ->setFileName(function ($originalFilename, $extension, $model) {
                    return md5($originalFilename) . '.' . $extension;
                })
                ->rules(['required', 'between:1,1']),

            Boolean::make('is_recommend', 'is_recommend', fn() => $isRecommend)
                ->sortable(),

            Select::make('status', 'stock_status_code')
                ->options($this->enumToSelectOptions(Status::class))
                ->displayUsingLabels()
                ->rules('required'),

            Text::make('title')
                ->sortable()
                ->rules('required', 'max:512'),

            Text::make('description')
                ->rules('required', 'max:512'),

            HasOne::make('Origin', 'origin', Origin::class),
            HasOne::make('Information', 'information', Information::class),
            HasOne::make('GenerateInformation', 'generateInformation', GenerateInformation::class),
            HasOne::make('Count', 'count', StockCount::class),

            DateTime::make('created_at')->sortable(),
            DateTime::make('updated_at')->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new EthnicityFilter(),
            new GenderFilter(),
            new StatusFilter(),
            new RecommendFilter(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
        ];
    }

    public function getConnection()
    {
        return 'api';
    }
}
