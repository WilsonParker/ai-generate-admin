<?php

namespace App\Nova\Resources\Images;

use App\Nova\Resources\BaseResource;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use AIGenerate\Services\AI\OpenAI\Enums\OpenAITypes;

class PromptGenerateResult extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-prompts',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Prompt>
     */
    public static $model = \App\Models\Prompt\PromptGenerateResult::class;
    public static $with = ['generate.prompt'];

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        try {
            return $this->generate->prompt->title;
        } catch (\Exception $e) {
            return '';
        }
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function getConnection()
    {
        return 'api';
    }

    public function fieldsForDetail(NovaRequest $request): array
    {
        if (!$this->result) {
            return $this->fields($request);
        } else {
            return match ($this->generate->prompt->prompt_type_code) {
                OpenAITypes::Image->value => $this->imageFields(),
                OpenAITypes::Chat->value => $this->chatFields(),
                OpenAITypes::Completion->value => $this->completionFields(),
            };
        }
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            DateTime::make('created_at')->sortable(),
        ];
    }

    private function imageFields(): array
    {
        $image = json_decode($this->result, true)['data'][0]['url'];
        return [
            Image::make('image')
                ->preview(function () use ($image) {
                    return $image;
                })
                ->maxWidth(300)
                ->disableDownload(),
        ];
    }

    private function chatFields(): array
    {
        $result = json_decode($this->result, true)['choices'][0]['message']['content'];
        return [
            Textarea::make('result')
                ->withMeta([
                    'value' => $result,
                ])->alwaysShow(),
        ];
    }

    private function completionFields(): array
    {
        $result = json_decode($this->result, true)['choices'][0]['text'];
        return [
            Textarea::make('result')
                ->withMeta([
                    'value' => $result,
                ])->alwaysShow(),
        ];
    }

    public function fieldsForUpdate(NovaRequest $request): array
    {
        return [];
    }
}
