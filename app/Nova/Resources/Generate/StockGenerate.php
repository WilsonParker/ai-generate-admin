<?php

namespace App\Nova\Resources\Generate;

use App\Nova\Fields\Images;
use App\Nova\Resources\BaseResource;
use App\Nova\Resources\Stock\Stock;
use App\Nova\Resources\User\User;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class StockGenerate extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-stocks',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Stock>
     */
    public static $model = \App\Models\Stock\StockGenerate::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'stock.title';

    public static $with = ['stock', 'user', 'images'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'stock_id',
        'user_id',
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
        return [
            ID::make()->sortable(),

            Images::make('Images', 'gallery')
                ->enableExistingMedia()
                ->conversionOnIndexView('gallery-thumbnail')
                ->conversionOnDetailView('gallery-thumbnail')
                ->setFileName(function ($originalFilename, $extension, $model) {
                    return md5($originalFilename) . '.' . $extension;
                })
                ->rules(['required', 'between:1,1']),

            Text::make('Ethnicity'),
            Text::make('Gender'),
            Number::make('Age'),
            Boolean::make('Is_skin_reality'),
            Boolean::make('Is_pose_variation'),
            Boolean::make('Is_public'),
            HasOne::make('Stock', 'stock', Stock::class),
            HasOne::make('User', 'user', User::class),

            DateTime::make('created_at')->sortable(),
            DateTime::make('updated_at')->sortable(),
        ];
    }

    public function getConnection()
    {
        return 'api';
    }
}
