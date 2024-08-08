<?php

namespace App\Nova\Resources\Mail;

use App\Http\Repositories\User\UserRepository;
use App\Nova\Actions\Mail\SendMailAction;
use App\Nova\Resources\BaseResource;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Template extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-mails',
    ];

    public static $model = \App\Service\Mail\Model\Template::class;

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Id'),
            Text::make('Name'),
            Text::make('Subject')
                ->showOnIndex(false),
            Boolean::make('IsActive', 'is_active')
                ->showOnIndex(false),
            Text::make('Tag'),
            Code::make('HtmlContent', 'html_content')
                ->language('htmlmixed'),

            DateTime::make('CreatedAt', 'created_at')
                ->showOnCreating(false)
                ->showOnUpdating(false),
            DateTime::make('UpdatedAt', 'updated_at')
                ->showOnCreating(false)
                ->showOnUpdating(false),
        ];
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
            SendMailAction::make(
                app()->make(UserRepository::class),
                app()->make(\App\Repository\Mail\SenderRepository::class)
            )->showOnDetail(),
        ];
    }
}
