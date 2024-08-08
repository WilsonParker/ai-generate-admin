<?php

namespace App\Nova\Resources\Mail;

use App\Nova\Resources\BaseResource;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Sender extends BaseResource
{
    public static $permissionsForAbilities = [
        'all' => 'manage-mails',
    ];

    public static $model = \App\Service\Mail\Model\Sender::class;

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Id')
                ->showOnCreating(false)
                ->showOnUpdating(false),
            Text::make('Name'),
            Text::make('Email'),
        ];
    }

}
