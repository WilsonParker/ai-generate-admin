<?php

namespace App\Nova\Resources\Stock;

use App\Nova\Resources\BaseResource;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class StockCount extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-stocks',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Stock>
     */
    public static $model = \App\Models\Stock\StockCount::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'stock_id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'stock_id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            // BelongsTo::make('Stock', 'stock', Stock::class),
            Number::make('generates')->sortable()->rules('required', 'min:0'),
            Number::make('views')->sortable()->rules('required', 'min:0'),
            Number::make('likes')->sortable()->rules('required', 'min:0'),
            Number::make('amazing')->sortable()->rules('required', 'min:0'),
            Number::make('good')->sortable()->rules('required', 'min:0'),
            Number::make('weird_face')->sortable()->rules('required', 'min:0'),
            Number::make('twisted')->sortable()->rules('required', 'min:0'),
            Number::make('different_picture')->sortable()->rules('required', 'min:0'),
            Number::make('not_working')->sortable()->rules('required', 'min:0'),
            Number::make('nsfw')->sortable()->rules('required', 'min:0'),
            Number::make('feedback')->sortable()->rules('required', 'min:0'),
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
