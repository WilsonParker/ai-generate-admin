<?php

namespace App\Service\Mail\Brevo\Enums;

enum ParamTypes: string
{
    case USER_NAME = 'user.name';
    case USER_PROFILE_IMAGE = 'user.profile.image';
    case BEST_PROMPT_THUMBNAIL = 'best.prompt.thumbnail';
    case BEST_PROMPT_NAME = 'best.prompt.name';
    case LATEST_PROMPT_THUMBNAIL = 'latest.prompt.thumbnail';
    case LATEST_PROMPT_NAME = 'latest.prompt.name';
    case PROMPT_THUMBNAIL = 'prompt.thumbnail';
    case PROMPT_NAME = 'prompt.name';
    case MYPAGE_LINK = 'mypage.link';
}
