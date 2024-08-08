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
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use AIGenerate\Services\Generate\Enums\Ratio;
use AIGenerate\Services\Generate\Enums\TextGenerateType;

class TextGenerate extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-stocks',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Stock>
     */
    public static $model = \App\Models\Generate\TextGenerate::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'generate.prompt';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'prompt',
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

            Textarea::make('prompt'),
            Select::make('type', 'type_code')
                ->options($this->enumToSelectOptions(TextGenerateType::class))
                ->displayUsingLabels()
                ->rules('required'),
            Select::make('ratio', 'ratio')
                ->options($this->enumToSelectOptions(Ratio::class))
                ->displayUsingLabels()
                ->rules('required'),
            Text::make('gender'),
            Text::make('ethnicity'),
            Number::make('Age'),
            Boolean::make('Is_skin_reality'),
            Boolean::make('Is_public'),

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
