<?php

namespace App\Nova\Actions;

use App\Models\Prompt\Prompt;
use App\Services\Auth\Facades\AuthService;
use App\Services\Prompt\PromptChatGenerateService;
use App\Services\Prompt\PromptCompletionGenerateService;
use App\Services\Prompt\PromptImageGenerateService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use AIGenerate\Services\AI\OpenAI\Enums\OpenAITypes;

class PromptGenerateAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection    $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $prompt = $models->first();
        if ($prompt->prompt_type_code == OpenAITypes::Chat->value) {
            $service = app()->make(PromptChatGenerateService::class);
        } else if ($prompt->prompt_type_code == OpenAITypes::Completion->value) {
            $service = app()->make(PromptCompletionGenerateService::class);
        } else if ($prompt->prompt_type_code == OpenAITypes::Image->value) {
            $service = app()->make(PromptImageGenerateService::class);
        }
        $user = AuthService::testUser();
        $validated = $fields->toArray();
        $promptGenerate = $service->store($prompt, $user, $validated);
        $promptGenerateResult = $service->callApi($promptGenerate, $validated);
        return Action::openInNewTab('/nova/resources/prompt-generate-results/' . $promptGenerateResult->id);
    }

    /**
     * Get the fields available on the action.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $id = $request->get('resourceId', $request->get('resources'));
        $prompt = Prompt::findOrFail($id);
        $options = $prompt->options
            ->map(fn($option) => Text::make($option->name, 'p' . $option->id)->rules('required'))
            ->toArray();
        return array_merge(
            $options,
            match ($prompt->prompt_type_code) {
                OpenAITypes::Image->value => $this->imageFields($prompt),
                OpenAITypes::Chat->value, OpenAITypes::Completion->value => $this->chatFields($prompt),
            }
        );
    }

    private function imageFields($prompt): array
    {
        return [
            Select::make('Size')
                  ->options([
                      '256x256' => '256',
                      '512x512' => '512',
                      '1024x1024' => '1024',
                  ])
                  ->displayUsingLabels()
                  ->rules('required'),
        ];
    }

    private function chatFields($prompt): array
    {
        $fields = [];
        if (isset($prompt->order)) {
            $fields[] = Text::make('order');
        }
        return $fields;
    }

}
