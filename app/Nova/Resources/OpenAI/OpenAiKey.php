<?php

namespace App\Nova\Resources\OpenAI;

use App\Nova\Resources\BaseResource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class OpenAiKey extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-prompts',
    ];
    
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<OpenAiKey>
     */
    public static $model = \App\Models\OpenAI\OpenAiKey::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
            ID::make()->sortable(),

            Text::make('key')
                ->rules('required', 'max:128'),

            Text::make('description')
                ->rules('nullable', 'max:128'),
        ];
    }

}
