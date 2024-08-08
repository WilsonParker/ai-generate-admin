<?php

namespace App\Nova\Resources\Prompt;

use App\Nova\Actions\PromptGenerateAction;
use App\Nova\Resources\BaseResource;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use AIGenerate\Models\Prompt\Enums\Status;
use AIGenerate\Services\AI\OpenAI\Enums\OpenAITypes;

class Prompt extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-prompts',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Prompt>
     */
    public static $model = \App\Models\Prompt\Prompt::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title'
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
            ID::make()->sortable(),

            Image::make('Thumbnail', 'thumbnail')
                ->detailWidth(250)
                ->indexWidth(100)
                ->showOnDetail()
                ->showOnIndex()
                ->showOnCreating(false)
                ->showOnUpdating(false)
                ->thumbnail(fn($value) => $value ? $value->getLink('gallery-thumbnail') : '')
                ->preview(fn($value) => $value ? $value->getLink('gallery-thumbnail') : '')
                ->disableDownload(),

            //            HasOne::make('Thumbnail', 'thumbnail', Thumbnail::class),
            //            HasMany::make('Images', 'images', MultipleMedia::class),

            Select::make('type', 'prompt_type_code')
                ->sortable()
                ->rules('required', 'exists:api.prompt_types,code')
                ->options($enumToSelect(OpenAITypes::class))
                ->displayUsingLabels(),

            Select::make('status', 'prompt_status_code')
                ->sortable()
                ->filterable()
                ->rules('required', 'exists:api.prompt_status,code')
                ->options($enumToSelect(Status::class))
                ->displayUsingLabels(),

            Currency::make('price', 'price_per_generate')
                ->locale('us')
                ->sortable()
                ->rules('required', 'regex:/^\d{1,3}+(\.\d{1,2})?$/', 'min:0'),

            Text::make('title')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('description')
                ->rules('required')
                ->hideFromIndex(),

            Textarea::make('template')
                ->hideFromIndex(),

            Text::make('guide')
                ->hideFromIndex(),

            Text::make('output_result')
                ->hideFromIndex(),

            DateTime::make('created_at'),
            DateTime::make('updated_at'),

            Images::make('Images', 'gallery')
                ->enableExistingMedia()
                ->showOnIndex(false)
                ->conversionOnDetailView('gallery-thumbnail')
                ->setFileName(function ($originalFilename, $extension, $model) {
                    return md5($originalFilename) . '.' . $extension;
                })
                ->rules(['required', 'between:1,5']), // validation rules

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
        $options = $this->options->mapWithKeys(fn($option) => [$option->name => 'p' . $option->id]);
        return [
            PromptGenerateAction::make()->withMeta(['options' => $options])->showOnDetail(),
        ];
    }

}
