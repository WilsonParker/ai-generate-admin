<?php

namespace App\Nova\Resources\Stock;

use App\Models\Stock\StockInformation;
use App\Nova\Resources\BaseResource;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use AIGenerate\Models\Stock\Enums\Ethnicity;
use AIGenerate\Models\Stock\Enums\Gender;

class Information extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-stocks',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Stock>
     */
    public static $model = StockInformation::class;

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
        $enumToSelect = function ($enum) {
            return collect($enum::cases())
                ->mapWithKeys(fn($item) => [$item->value => $item->name])
                ->toArray();
        };

        return [
            Select::make('gender')
                ->options($this->enumToSelectOptions(Gender::class))
                ->displayUsingLabels()
                ->rules('required', 'max:8'),

            Select::make('ethnicity', 'ethnicity')
                ->options($this->enumToSelectOptions(Ethnicity::class))
                ->displayUsingLabels()
                ->rules('required', 'max:32'),

            Number::make('people_number')
                ->sortable()
                ->rules('required', 'max:8'),

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
