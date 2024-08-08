<?php

namespace App\Nova\Resources\Stock;

use App\Models\Stock\AI\StockGenerateInformation;
use App\Nova\Resources\BaseResource;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateInformation extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-stocks',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Stock>
     */
    public static $model = StockGenerateInformation::class;

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
            Text::make('race')
                ->rules('nullable'),
            Text::make('gender')
                ->rules('nullable'),
            Text::make('clothing')
                ->rules('nullable'),
            Text::make('emotion')
                ->rules('nullable'),
            Text::make('facial_expression')
                ->rules('nullable'),
            Text::make('movement')
                ->rules('nullable'),
            Text::make('camera_composition')
                ->rules('nullable'),
            Text::make('photo_type')
                ->rules('nullable'),
            Text::make('background')
                ->rules('nullable'),
            Text::make('time_zone')
                ->rules('nullable'),
            Text::make('other_additional_explanations')
                ->rules('nullable'),

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

}
