<?php

namespace App\Nova\Actions\Mail;

use App\Http\Repositories\User\UserRepository;
use App\Repository\Mail\SenderRepository;
use App\Service\Mail\MailService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class SendMailAction extends Action
{
    use InteractsWithQueue, Queueable;

    public function __construct(
        private readonly UserRepository   $userRepository,
        private readonly SenderRepository $senderRepository
    ) {}

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection    $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        try {
            $service = app()->make(MailService::class);
            $sendEmail = $service->send($models->first(), $this->senderRepository->first(), $this->userRepository->showOrFail($fields->get('user')));
        } catch (\Throwable $throwable) {
            return Action::danger($throwable->getMessage());
        }
        // return Action::openInNewTab('/nova/resources/prompt-generate-results/' . $promptGenerateResult->id);
    }

    /**
     * Get the fields available on the action.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('User')
                  ->options($this->userRepository
                      ->all()
                      ->mapWithKeys(fn($user) => [$user->id => $user->email])
                  ),
        ];
    }

}
